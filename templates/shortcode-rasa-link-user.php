<div class="page">
    <h1><?php esc_html_e('Rasa Link', 'rasa-link'); ?></h1>

    <!-- tabs -->
    <div class="pcss3t pcss3t-effect-scale pcss3t-theme-1">
        <input type="radio" name="pcss3t" checked id="tab1" class="tab-content-first">
        <label for="tab1"><?php esc_html_e('Shorten Link', 'rasa-link'); ?></label>

        <input type="radio" name="pcss3t" id="tab2" class="tab-content-2">
        <label for="tab2"><?php esc_html_e('Advanced Shorten Link', 'rasa-link'); ?></label>

        <ul>
            <li class="tab-content tab-content-first typography">
                <h2 class="section-title"><?php esc_html_e('Shorten Link', 'rasa-link'); ?></h2>
                <form action="#" id="shorten-link-form" method="post">
                    <label for="link"><?php esc_html_e('Link', 'rasa-link'); ?></label>
                    <input type="url" name="link" id="link" placeholder="<?php esc_attr_e('Enter the link to shorten', 'rasa-link'); ?>" required><br>
                    <button type="submit" class="btn-shorten"><?php esc_html_e('Shorten', 'rasa-link'); ?></button><br>
                    <div class="message-link" id="rasa_message"></div>
                </form>
            </li>

            <li class="tab-content tab-content-2 typography">
                <h2 class="section-title"><?php esc_html_e('Advanced Shorten Link', 'rasa-link'); ?></h2>
                <form action="#" id="advanced-shorten-link-form" method="post">
                    <label for="link_advanced"><?php esc_html_e('Link', 'rasa-link'); ?></label>
                    <input type="url" name="link" id="link_advanced" placeholder="<?php esc_attr_e('Enter the link to shorten', 'rasa-link'); ?>" required><br>

                    <label for="name_channel_advanced"><?php esc_html_e('Channel', 'rasa-link'); ?></label>
                    <select name="name_channel" id="name_channel_advanced" required>
                        <?php
                        foreach ($channels as $channel) {
                            echo '<option value="' . esc_attr($channel['id']) . '">' . esc_html($channel['name']) . '</option>';
                        }
                        ?>
                    </select><br>

                    <label for="name_link_advanced"><?php esc_html_e('Name Link', 'rasa-link'); ?></label>
                    <input type="text" name="name_link" id="name_link_advanced" placeholder="<?php esc_attr_e('Enter custom link name', 'rasa-link'); ?>" required><br>

                    <button type="submit" class="btn-shorten_advanced"><?php esc_html_e('Shorten', 'rasa-link'); ?></button><br>
                    <div class="message-link" id="rasa_message_advanced"></div>
                </form>
            </li>
        </ul>
    </div>
    <!--/ tabs -->
</div>
