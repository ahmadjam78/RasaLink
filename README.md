# Rasa Link
Easily shorten your long links

# Rasa Link Plugin for WordPress

The **Rasa Link Plugin** is a WordPress plugin that allows you to shorten URLs and manage channels using the Rasa API. With this plugin, you can easily generate short links, manage channels, and integrate them into your WordPress site via shortcodes.

## Features

- **Shorten URLs**: Easily shorten long URLs through a simple user interface.
- **Manage Channels**: Add and manage channels for URL shortening.
- **API Integration**: Uses the Rasa API for URL shortening and channel management.
- **Customizable**: Plugin provides hooks and shortcodes to integrate seamlessly into your theme or custom pages.

## Installation

1. Download the `rasa-link` plugin.
2. Upload the plugin folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Configuration

### Admin Menu

Once the plugin is activated, you can configure the settings through the admin menu:

1. Go to **Rasa Link** in the WordPress admin sidebar.
2. Enter your **API Key** in the provided field.
3. Add and manage channels for URL shortening.

### Shortcode Usage

You can use the following shortcode to display the URL shortening form on any page or post:

```[rasa_link_user]```

### Adding and Managing Channels

You can add a new channel by entering the **Channel Name** in the admin settings and saving it. The channel will be available when shortening URLs.

## AJAX and API Integration

The plugin integrates with the Rasa API to shorten URLs and manage channels. The API keys are saved in the WordPress options and used for API requests.

### Actions

- **save_api_key**: Saves the API key used to interact with the Rasa API.
- **save_channel**: Saves a new channel to be used for URL shortening.

## Developer Notes

- **Hooks**: The plugin utilizes actions to hook into WordPress, such as `admin_menu`, `wp_ajax_save_api_key`, and `wp_ajax_save_channel`.
- **Localization**: The plugin includes localization support for both English and Persian.
- **Security**: Nonces are used to verify requests and prevent CSRF attacks.

## Code Structure

### Rasa_Link Class

The `Rasa_Link` class handles the main functionality of interacting with the Rasa API, shortening URLs, and fetching channel data.

- **Methods**:
    - `rasa_link_user_shortcode`: Displays the URL shortening form.
    - `rasa_shorten_link_callback`: Handles URL shortening requests.
    - `rasa_shorten_link_advanced_callback`: Handles advanced URL shortening requests with a custom name and channel.
    - `rasa_link_api`: Makes API requests to Rasa and handles responses.

### Rasa_Settings Class

The `Rasa_Settings` class handles the admin settings page, where users can manage their API keys and channels.

- **Methods**:
    - `add_admin_menu`: Registers the settings menu in the WordPress admin.
    - `render_settings_page`: Renders the settings page where users can configure the plugin.
    - `enqueue_admin_assets`: Enqueues styles and scripts for the admin settings page.
    - `handle_save_api_key`: Handles saving the API key.
    - `handle_save_channel`: Handles adding new channels.

## Troubleshooting

- **"API key cannot be empty" error**: Ensure that you have entered a valid API key in the settings page.
- **"Link cannot be empty" error**: Make sure you are providing a valid URL when using the URL shortening feature.
- **Connection errors**: If you experience issues with connecting to the server, verify your API key and the Rasa service status.

## License

This plugin is licensed under the GPL v2 or later.

## Version

- **Version**: 1.0.0
- **Release Date**: May 2025

---

For any issues or feature requests, please contact the plugin developer or create an issue in the plugin's GitHub repository.
