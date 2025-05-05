jQuery(document).ready(function($) {
    function handleShortenClick(selector, action, linkSelector, nameSelector, channelSelector, messageSelector) {
        $(selector).on('click', function(e) {
            e.preventDefault();

            const link = $(linkSelector).val();
            const name = nameSelector ? $(nameSelector).val() : '';
            const channel = channelSelector ? $(channelSelector).val() : '';
            const $message = $(messageSelector);

            $message.show();

            if (!link) {
                $message.html(rasa_messages.error_empty_link);
                return;
            }

            $message.html(rasa_messages.processing);

            $.ajax({
                type: 'POST',
                url: rasa_ajax_object.ajax_url,
                data: {
                    action: action,
                    link: link,
                    name: name,
                    channel: channel,
                    _ajax_nonce: rasa_ajax_object.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $message.html(rasa_messages.success_short_link + ": " + response.data);
                    } else {
                        $message.html(rasa_messages.error_general + ': ' + (response.data || rasa_messages.error_invalid_response));
                    }
                },
                error: function(xhr, status, error) {
                    $message.html(rasa_messages.error_server + ': ' + error);
                }
            });
        });
    }

    handleShortenClick(
        '.btn-shorten',
        'rasa_shorten_link',
        '#link',
        '#name_link',
        '#name_channel',
        '#rasa_message'
    );

    handleShortenClick(
        '.btn-shorten_advanced',
        'rasa_shorten_link_advanced',
        '#link_advanced',
        '#name_link_advanced',
        '#name_channel_advanced',
        '#rasa_message_advanced'
    );
});
