jQuery(document).ready(function($) {
    function handleShortenClick(selector, action, linkSelector, nameSelector, channelSelector, messageSelector) {
        $(selector).on('click', function(e) {
            e.preventDefault();

            const link = $(linkSelector).val();
            const name = nameSelector ? $(nameSelector).val() : '';
            const channel = channelSelector ? $(channelSelector).val() : '';
            const $message = $(messageSelector);

            if (!link) {
                $message.html('لطفاً لینک را وارد کنید.');
                return;
            }

            $message.html('در حال پردازش...');

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
                        $message.html(response.data);
                    } else {
                        $message.html('خطا: ' + (response.data || 'پاسخ نامعتبر از سرور.'));
                    }
                },
                error: function(xhr, status, error) {
                    $message.html('خطایی رخ داده است: ' + error);
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
