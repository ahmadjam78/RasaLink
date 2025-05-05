<?php
/**
 * Plugin Name: Rasa Link
 * Plugin URI: https://rasa.li
 * Description: Easily shorten your long links
 * Version: 1.0.0
 * Author: Rasa
 * Author URI: https://rasa.li
 * License: GPL2
 */

defined('ABSPATH') or die('No script kiddies please!');

// Define plugin constants
define('RASA_LINK_VERSION', '1.0.0');
define('RASA_LINK_PLUGIN_FILE', __FILE__);
define('RASA_LINK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RASA_LINK_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once RASA_LINK_PLUGIN_DIR . 'includes/class-main.php';

/**
 * Activate the plugin.
 */
function rasa_link_plugin_activate() {
    // Perform any activation tasks here
    if (!current_user_can('activate_plugins')) {
        return;
    }
}
register_activation_hook(RASA_LINK_PLUGIN_FILE, 'rasa_link_plugin_activate');

/**
 * Deactivate the plugin.
 */
function rasa_link_plugin_deactivate() {
    // Perform any deactivation tasks here
}
register_deactivation_hook(RASA_LINK_PLUGIN_FILE, 'rasa_link_plugin_deactivate');

/**
 * Initialize and run the plugin.
 */
function run_rasa_link_plugin() {
    $plugin = new Rasa_Link();
    $plugin->run();
}

add_action('plugins_loaded', 'run_rasa_link_plugin');
