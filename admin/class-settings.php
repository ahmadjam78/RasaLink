<?php

if (!defined('ABSPATH')) exit;

class Rasa_Settings {

    /**
     * Constructor - Initializes the plugin settings page and hooks.
     *
     * @version 1.0.0
     *
     * This method adds actions for registering the admin menu, enqueuing scripts/styles,
     * and handling AJAX requests for saving the API key and channels.
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_ajax_save_api_key', [$this, 'handle_save_api_key']);
        add_action('wp_ajax_save_channel', [$this, 'handle_save_channel']);
    }

    /**
     * Register the admin menu for Rasa Link settings.
     *
     * @version 1.0.0
     *
     * This method creates a new menu item in the WordPress admin sidebar for the settings page.
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Rasa Link Settings', 'rasa-link'),
            __('Rasa Link', 'rasa-link'),
            'manage_options',
            'rasa-link-settings',
            [$this, 'render_settings_page'],
            RASA_LINK_PLUGIN_URL . 'assets/img/icon-logo.png',
            81
        );
    }

    /**
     * Render the settings page for the plugin.
     *
     * @version 1.0.0
     *
     * This method fetches the API key, URLs, and channels from the Rasa API and renders the settings page.
     */
    public function render_settings_page() {
        $api_key = get_option('rasa_api_key');

        $rasa = new Rasa_Link();
        $result_urls     = $rasa->rasa_link_api('https://rasa.li/api/urls', 'GET', $api_key);
        $result_channels = $rasa->rasa_link_api('https://rasa.li/api/channels', 'GET', $api_key);

        $urls     = $result_urls['data']['data']['urls'] ?? [];
        $channels = $result_channels['data']['data']['channels'] ?? [];

        include RASA_LINK_PLUGIN_DIR . 'templates/settings-page.php';
    }

    /**
     * Enqueue admin styles and scripts for the settings page.
     *
     * @version 1.0.0
     *
     * This method enqueues the necessary styles and JavaScript files for the admin settings page.
     * It also localizes AJAX-related data such as URLs and nonce for security.
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'toplevel_page_rasa-link-settings') {
            return;
        }

        wp_enqueue_style(
            'rasa-link-admin-style',
            RASA_LINK_PLUGIN_URL . 'assets/css/admin.min.css',
            [],
            RASA_LINK_VERSION
        );

        wp_enqueue_script(
            'rasa-link-admin-script',
            RASA_LINK_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            RASA_LINK_VERSION,
            true
        );

        wp_localize_script('rasa-link-admin-script', 'rasa_ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('rasa_nonce'),
        ]);

        wp_localize_script('rasa-link-admin-script', 'rasa_messages', [
            'error_server'          => __('An error occurred while connecting to the server.', 'rasa-link'),
            'error_empty_api_key'   => __('Please enter the API key.', 'rasa-link'),
            'error_empty_channel'   => __('Please enter the channel name.', 'rasa-link'),
            'success_api_key_saved' => __('API key saved successfully.', 'rasa-link'),
            'success_channel_saved' => __('Channel saved successfully.', 'rasa-link'),
            'error_general'         => __('An error occurred.', 'rasa-link')
        ]);
    }

    /**
     * Handle saving the API key via AJAX request.
     *
     * @version 1.0.0
     *
     * This method processes the API key submitted via AJAX, sanitizes it, and saves it in the WordPress database.
     */
    public function handle_save_api_key() {
        check_ajax_referer('rasa_nonce');

        $api_key = sanitize_text_field($_POST['api_key']);

        if (!empty($api_key)) {
            update_option('rasa_api_key', $api_key);
            wp_send_json_success(['message' => __('API key saved successfully.', 'rasa-link')]);
        } else {
            wp_send_json_error(['message' => __('API key cannot be empty.', 'rasa-link')]);
        }
    }

    /**
     * Handle saving a new channel via AJAX request.
     *
     * @version 1.0.0
     *
     * This method processes the channel name submitted via AJAX and saves it using the Rasa API.
     */
    public function handle_save_channel() {
        check_ajax_referer('rasa_nonce');

        $name = sanitize_text_field($_POST['name']);

        if (!empty($name)) {
            $api_key = get_option('rasa_api_key');
            $payload = ['name' => $name];

            $rasa = new Rasa_Link();
            $result = $rasa->rasa_link_api('https://rasa.li/api/channel/add', 'POST', $api_key, $payload);

            if ($result['success']) {
                if ($result['data']['id'] != null) {
                    wp_send_json_success([
                        'message' => sprintf(__('Channel ID: %s', 'rasa-link'), $result['data']['id']),
                    ]);
                } else {
                    wp_send_json_error(['message' => $result['data']['message']]);
                }
            } else {
                wp_send_json_error([
                    'message' => $result['error'],
                ]);
            }
        } else {
            wp_send_json_error(['message' => __('Name cannot be empty.', 'rasa-link')]);
        }
    }
}
