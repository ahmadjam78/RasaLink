<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Rasa_Link
{
    /**
     * Constructor - Called when the class is instantiated
     */
    public function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load required files and classes
     */
    private function load_dependencies() {
        require_once RASA_LINK_PLUGIN_DIR . '/admin/class-settings.php';
    }

    /**
     * Initialize the plugin
     */
    public function run() {
        add_shortcode('rasa_link_user',  [$this, 'rasa_link_user_shortcode']);
        add_action('wp_ajax_rasa_shorten_link', [$this, 'rasa_shorten_link_callback']);
        add_action('wp_ajax_nopriv_rasa_shorten_link', [$this, 'rasa_shorten_link_callback']);
        add_action('wp_ajax_rasa_shorten_link_advanced', [$this, 'rasa_shorten_link_advanced_callback']);
        add_action('wp_ajax_nopriv_rasa_shorten_link_advanced', [$this, 'rasa_shorten_link_advanced_callback']);
    }

    /**
     * Render plugin settings page
     */
    private function init_hooks() {
        new Rasa_Settings();
    }

    /**
     * Handle user shortcode rendering
     */
    function rasa_link_user_shortcode() {
        wp_enqueue_style('rasa-link-shortcode-style', RASA_LINK_PLUGIN_URL . 'assets/css/frontend.css', [], RASA_LINK_VERSION);
        wp_enqueue_script('rasa-link-shortcode-script', RASA_LINK_PLUGIN_URL . 'assets/js/frontend.js', ['jquery'], RASA_LINK_VERSION, true);

        wp_localize_script('rasa-link-shortcode-script', 'rasa_ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('rasa_nonce_action'),
        ]);

        $channels = $this->get_channels();

        ob_start();
        include RASA_LINK_PLUGIN_DIR . 'templates/shortcode-rasa-link-user.php';
        return ob_get_clean();
    }

    /**
     * Fetch channels from API
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
     * Callback for shortening link
     */
    function rasa_shorten_link_callback() {
        check_ajax_referer('rasa_nonce_action', '_ajax_nonce');

        $link = isset($_POST['link']) ? esc_url_raw($_POST['link']) : '';

        if (empty($link)) {
            wp_send_json_error('لینک نمی‌تواند خالی باشد.');
        }

        $this->process_shorten_link($link);
    }

    /**
     * Callback for advanced shortening link
     */
    function rasa_shorten_link_advanced_callback() {
        check_ajax_referer('rasa_nonce_action', '_ajax_nonce');

        $link = isset($_POST['link']) ? esc_url_raw($_POST['link']) : '';
        $name = isset($_POST['name']) ? esc_url_raw($_POST['name']) : '';
        $channel = isset($_POST['channel']) ? esc_url_raw($_POST['channel']) : '';

        if (empty($link)) {
            wp_send_json_error('لینک نمی‌تواند خالی باشد.');
        }

        $this->process_shorten_link($link, $name, $channel);
    }

    /**
     * Common function for shortening links
     */
    private function process_shorten_link($link, $name = '', $channel = '') {
        $api_key = get_option('rasa_api_key');

        if (empty($api_key)) {
            wp_send_json_error('API key نمی‌تواند خالی باشد.');
        }

        $payload = [
            'url' => $link,
            'status' => 'private',
            'custom' => $name,
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
     * Perform API call
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
