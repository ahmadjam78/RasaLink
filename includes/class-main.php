<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Rasa_Link
{
    /**
     * Constructor - Initializes the plugin by loading dependencies and setting up hooks.
     *
     * @version 1.0.0
     *
     * This method is automatically called when the class is instantiated. It loads necessary
     * dependencies and registers the hooks required to run the plugin.
     */
    public function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load the required dependencies for the plugin.
     *
     * @version 1.0.0
     *
     * This method includes the required PHP files such as settings for the admin panel.
     */
    private function load_dependencies() {
        require_once RASA_LINK_PLUGIN_DIR . '/admin/class-settings.php';
    }

    /**
     * Run the plugin by setting up actions and shortcodes.
     *
     * @version 1.0.0
     *
     * This method adds the necessary actions and shortcodes to the WordPress system.
     * It is called during the 'init' action to initialize everything.
     */
    public function run() {
        add_action('init', [$this, 'rasa_load_textdomain']);
        add_shortcode('rasa_link_user',  [$this, 'rasa_link_user_shortcode']);
        add_action('wp_ajax_rasa_shorten_link', [$this, 'rasa_shorten_link_callback']);
        add_action('wp_ajax_nopriv_rasa_shorten_link', [$this, 'rasa_shorten_link_callback']);
        add_action('wp_ajax_rasa_shorten_link_advanced', [$this, 'rasa_shorten_link_advanced_callback']);
        add_action('wp_ajax_nopriv_rasa_shorten_link_advanced', [$this, 'rasa_shorten_link_advanced_callback']);
    }

    /**
     * Load the text domain for translation files.
     *
     * @version 1.0.0
     *
     * This method loads the plugin's language files so that the strings can be translated.
     */
    function rasa_load_textdomain() {
        load_plugin_textdomain('rasa-link', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Initialize hooks for the settings page.
     *
     * @version 1.0.0
     *
     * This method initializes the settings page for the plugin by creating an instance
     * of the Rasa_Settings class.
     */
    private function init_hooks() {
        new Rasa_Settings();
    }

    /**
     * Render the user shortcode and enqueue required scripts and styles.
     *
     * @version 1.0.0
     *
     * This method handles the [rasa_link_user] shortcode. It enqueues frontend styles and
     * scripts for the plugin and localizes messages for Ajax calls.
     *
     * @return string Rendered HTML for the user shortcode.
     */
    function rasa_link_user_shortcode() {
        wp_enqueue_style('rasa-link-shortcode-style', RASA_LINK_PLUGIN_URL . 'assets/css/frontend.min.css', [], RASA_LINK_VERSION);
        wp_enqueue_script('rasa-link-shortcode-script', RASA_LINK_PLUGIN_URL . 'assets/js/frontend.js', ['jquery'], RASA_LINK_VERSION, true);

        wp_localize_script('rasa-link-shortcode-script', 'rasa_ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('rasa_nonce_action'),
        ]);

        wp_localize_script('rasa-link-shortcode-script', 'rasa_messages', [
            'error_empty_link'           => __('Please enter the link.', 'rasa-link'),
            'processing'                 => __('Processing...', 'rasa-link'),
            'success_short_link'         => __('Short Link', 'rasa-link'),
            'error_general'              => __('An error occurred.', 'rasa-link'),
            'error_invalid_response'     => __('Invalid response from the server.', 'rasa-link'),
            'error_server'               => __('An error occurred while connecting to the server.', 'rasa-link')
        ]);

        $channels = $this->get_channels();

        ob_start();
        include RASA_LINK_PLUGIN_DIR . 'templates/shortcode-rasa-link-user.php';
        return ob_get_clean();
    }

    /**
     * Fetch the available channels from the Rasa API.
     *
     * @version 1.0.0
     *
     * This method retrieves the list of channels from the API using the stored API key.
     *
     * @return array List of channels fetched from the API.
     */
    private function get_channels() {
        $api_key = get_option('rasa_api_key');
        $result = $this->rasa_link_api('https://rasa.li/api/channels', 'GET', $api_key);

        if ($result['success']) {
            return $result['data']['data']['channels'];
        }

        return [];
    }

    /**
     * Handle the Ajax callback for shortening a link.
     *
     * @version 1.0.0
     *
     * This method is triggered when the Ajax request is made to shorten a link. It processes
     * the link and returns a success or error message.
     */
    function rasa_shorten_link_callback() {
        check_ajax_referer('rasa_nonce_action', '_ajax_nonce');

        $link = isset($_POST['link']) ? esc_url_raw($_POST['link']) : '';

        if (empty($link)) {
            wp_send_json_error(__('Link cannot be empty.', 'rasa-link'));
        }

        $this->process_shorten_link($link);
    }

    /**
     * Handle the Ajax callback for advanced link shortening.
     *
     * @version 1.0.0
     *
     * This method is triggered for advanced shortening, where a user can specify a name and channel.
     * It processes the link, name, and channel data, and returns a success or error message.
     */
    function rasa_shorten_link_advanced_callback() {
        check_ajax_referer('rasa_nonce_action', '_ajax_nonce');

        $link    = isset($_POST['link']) ? esc_url_raw($_POST['link']) : '';
        $name    = isset($_POST['name']) ? esc_url_raw($_POST['name']) : '';
        $channel = isset($_POST['channel']) ? esc_url_raw($_POST['channel']) : '';

        if (empty($link)) {
            wp_send_json_error(__('Link cannot be empty.', 'rasa-link'));
        }

        $this->process_shorten_link($link, $name, $channel);
    }

    /**
     * Process the link shortening request.
     *
     * @version 1.0.0
     *
     * This method communicates with the Rasa API to shorten the given link and returns
     * the shortened URL or an error message.
     *
     * @param string $link The link to be shortened.
     * @param string $name Optional custom name for the shortened link.
     * @param string $channel Optional channel to associate with the link.
     */
    private function process_shorten_link($link, $name = '', $channel = '') {
        $api_key = get_option('rasa_api_key');

        if (empty($api_key)) {
            wp_send_json_error(__('API key cannot be empty.', 'rasa-link'));
        }

        $payload = [
            'url'     => $link,
            'status'  => 'private',
            'custom'  => $name,
            'channel' => $channel,
        ];

        $result = $this->rasa_link_api('https://rasa.li/api/url/add', 'POST', $api_key, $payload);

        if ($result['success']) {
            wp_send_json_success($result['data']['shorturl']);
        } else {
            wp_send_json_error($result['error']);
        }
    }

    /**
     * Make a request to the Rasa API.
     *
     * @version 1.0.0
     *
     * This method handles making HTTP requests to the Rasa API using WordPress's `wp_remote_request`.
     * It sends the request and processes the response.
     *
     * @param string $api_link The URL of the Rasa API endpoint.
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param string $api_key The API key used for authorization.
     * @param array  $payload The data to be sent with the request (optional).
     * @return array The result of the API request, including success or error information.
     */
    public function rasa_link_api($api_link, $method, $api_key, $payload = []) {
        $response = wp_remote_request($api_link, [
            'method'    => $method,
            'headers'   => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/json',
            ],
            'body'      => !empty($payload) ? json_encode($payload) : null,
            'timeout'   => 10,
            'sslverify' => false,
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'error'   => $response->get_error_message()
            ];
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        return [
            'success' => true,
            'data'    => $data
        ];
    }
}
