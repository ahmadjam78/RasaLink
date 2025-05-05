<?php
/**
 * Rasa Link Admin Page (SEO Optimized)
 */
?>

<main class="page">
    <header class="rasa-container">
        <div>
            <h1 class="welcome"><?php esc_html_e('Welcome', 'rasa-link'); ?></h1>
            <p><strong>Rasa Link</strong> - <?php esc_html_e('Easily shorten your long links', 'rasa-link'); ?></p>
            <div class="rasa-logo">
                <img src="<?php echo esc_url(RASA_LINK_PLUGIN_URL . 'assets/img/logo.png'); ?>"
                     alt="<?php esc_attr_e('Rasa Link Logo', 'rasa-link'); ?>">
                <span class="rasa-version">Version 1.0.0</span>
            </div>
        </div>
    </header>

    <section class="tabs">
        <h2><?php esc_html_e('Rasa Link Dashboard', 'rasa-link'); ?></h2>

        <!-- tabs -->
        <div class="pcss3t pcss3t-effect-scale pcss3t-theme-1" role="tablist">
            <input type="radio" name="pcss3t" checked id="tab1" class="tab-content-first">
            <label for="tab1">API Key</label>

            <input type="radio" name="pcss3t" id="tab2" class="tab-content-2">
            <label for="tab2">Shortcode</label>

            <input type="radio" name="pcss3t" id="tab3" class="tab-content-3">
            <label for="tab3">Links</label>

            <input type="radio" name="pcss3t" id="tab5" class="tab-content-last">
            <label for="tab5">Channels</label>

            <ul>
                <li class="tab-content tab-content-first typography" role="tabpanel">
                    <h3><?php esc_html_e('API Key', 'rasa-link'); ?></h3>
                    <p><?php esc_html_e('An API key is required to process requests.', 'rasa-link'); ?></p>
                    <form action="#" method="post">
                        <label for="api-key">API Key</label>
                        <input id="api-key" name="api-key" type="text" placeholder="API Key"
                               value="<?php echo !empty($api_key) ? esc_attr($api_key) : ''; ?>">
                        <button class="btn-save" aria-label="<?php esc_attr_e('Save API Key', 'rasa-link'); ?>">
                            <?php esc_html_e('Save', 'rasa-link'); ?>
                        </button>
                    </form>
                </li>

                <li class="tab-content tab-content-2 typography" role="tabpanel">
                    <h3><?php esc_html_e('Shortcode', 'rasa-link'); ?></h3>
                    <p><code>[rasa_link_user]</code></p>
                </li>

                <li class="tab-content tab-content-3 typography" role="tabpanel">
                    <h3><?php esc_html_e('Links', 'rasa-link'); ?></h3>
                    <div class="table-container">
                        <table>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th><?php esc_html_e('Short Link', 'rasa-link'); ?></th>
                                <th><?php esc_html_e('Long Link', 'rasa-link'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($urls as $url) : ?>
                                <tr>
                                    <td><?php echo esc_html($url['id']); ?></td>
                                    <td><?php echo esc_html($url['shorturl']); ?></td>
                                    <td><?php echo esc_html($url['longurl']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </li>

                <li class="tab-content tab-content-last typography" role="tabpanel">
                    <h3><?php esc_html_e('Channels', 'rasa-link'); ?></h3>
                    <form action="#" method="post">
                        <label for="name-channel">Name</label>
                        <input id="name-channel" name="name-channel" type="text" placeholder="<?php esc_attr_e('Name', 'rasa-link'); ?>">
                        <button class="btn-save-channel" aria-label="<?php esc_attr_e('Save Channel', 'rasa-link'); ?>">
                            <?php esc_html_e('Save', 'rasa-link'); ?>
                        </button>
                    </form>

                    <div class="table-container">
                        <table>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th><?php esc_html_e('Name', 'rasa-link'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($channels as $channel) : ?>
                                <tr>
                                    <td><?php echo esc_html($channel['id']); ?></td>
                                    <td><?php echo esc_html($channel['name']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</main>
