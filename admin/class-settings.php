<?php

if (!defined('ABSPATH')) exit;

class Rasa_Settings {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_ajax_save_api_key', [$this, 'handle_save_api_key']);
        add_action('wp_ajax_save_channel', [$this, 'handle_save_channel']);
    }

    /**
     * Register admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'Rasa Link Settings',
            'Rasa Link',
            'manage_options',
            'rasa-link-settings',
            [$this, 'render_settings_page'],
            RASA_LINK_PLUGIN_URL . 'assets/img/icon-logo.png',
            81
        );
    }

    /**
     * Render the settings page
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
     * Enqueue admin styles and scripts
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'toplevel_page_rasa-link-settings') {
            return;
        }

        wp_enqueue_style(
            'rasa-link-admin-style',
            RASA_LINK_PLUGIN_URL . 'assets/css/admin.css',
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
            'nonce'    => wp_create_nonce('save_channel_nonce'),
        ]);
    }

    /**
     * Handle saving API key via AJAX
     */
    public function handle_save_api_key() {
        check_ajax_referer('save_api_key_nonce');

        $api_key = sanitize_text_field($_POST['api_key']);

        if (!empty($api_key)) {
            update_option('rasa_api_key', $api_key);
            wp_send_json_success(['message' => 'کلید API با موفقیت ذخیره شد.']);
        } else {
            wp_send_json_error(['message' => 'کلید API نمی‌تواند خالی باشد.']);
        }
    }

    /**
     * Handle saving new channel via AJAX
     */
    public function handle_save_channel() {
        check_ajax_referer('save_channel_nonce');

        $name = sanitize_text_field($_POST['name']);

        if (!empty($name)) {
            $api_key = get_option('rasa_api_key');
            $payload = ['name' => $name];

            $rasa = new Rasa_Link();
            $result = $rasa->rasa_link_api('https://rasa.li/api/channel/add', 'POST', $api_key, $payload);

            if ($result['success']) {
                wp_send_json_success([
                    'message' => $result['data']['id'],
                ]);
            } else {
                wp_send_json_error([
                    'message' => $result['error'],
                ]);
            }
        } else {
            wp_send_json_error(['message' => 'نام نمی‌تواند خالی باشد.']);
        }
    }
}
