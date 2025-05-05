<div class="page">
    <h1>Rasa Link</h1>

    <!-- tabs -->
    <div class="pcss3t pcss3t-effect-scale pcss3t-theme-1">
        <input type="radio" name="pcss3t" checked id="tab1" class="tab-content-first">
        <label for="tab1">Shorten Link</label>

        <input type="radio" name="pcss3t" id="tab2" class="tab-content-2">
        <label for="tab2">Advanced Shorten Link</label>

        <ul>
            <li class="tab-content tab-content-first typography">
                <h2 class="section-title">Shorten Link</h2>
                <form action="#" id="shorten-link-form" method="post">
                    <label for="link">Link</label>
                    <input type="url" name="link" id="link" placeholder="Enter the link to shorten" required><br>
                    <button type="submit" class="btn-shorten">Shorten</button><br>
                    <div id="rasa_message"></div>
                </form>
            </li>

            <li class="tab-content tab-content-2 typography">
                <h2 class="section-title">Advanced Shorten Link</h2>
                <form action="#" id="advanced-shorten-link-form" method="post">
                    <label for="link_advanced">Link</label>
                    <input type="url" name="link" id="link_advanced" placeholder="Enter the link to shorten" required><br>
                    <label for="name_channel_advanced">Channel</label>
                    <select name="name_channel" id="name_channel_advanced" required>
                        <?php
                        foreach ($channels as $channel) {
                            echo '<option value="' . esc_attr($channel['id']) . '">' . esc_html($channel['name']) . '</option>';
                        }
                        ?>
                    </select><br>
                    <label for="name_link_advanced">Name Link</label>
                    <input type="text" name="name_link" id="name_link_advanced" placeholder="Enter custom link name" required><br>

                    <button type="submit" class="btn-shorten_advanced">Shorten</button><br>
                    <div id="rasa_message_advanced"></div>
                </form>
            </li>
        </ul>
    </div>
    <!--/ tabs -->
</div>
