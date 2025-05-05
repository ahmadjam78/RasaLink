jQuery(document).ready(function($) {
    function sendAjax(action, data, successMessage) {
        data.action = action;
        data._ajax_nonce = rasa_ajax_object.nonce;

        $.post(rasa_ajax_object.ajax_url, data)
            .done(function(response) {
                if (response.success) {
                    alert(response.data.message || successMessage);
                } else {
                    alert(response.data.message || rasa_messages.error_general);
                }
            })
            .fail(function() {
                alert(rasa_messages.error_server);
            });
    }

    $('.btn-save').on('click', function(e) {
        e.preventDefault();
        const apiKey = $('input[name="api-key"]').val().trim();

        if (!apiKey) {
            alert(rasa_messages.error_empty_api_key);
            return;
        }

        sendAjax('save_api_key', { api_key: apiKey }, rasa_messages.success_api_key_saved);
    });

    $('.btn-save-channel').on('click', function(e) {
        e.preventDefault();
        const name = $('input[name="name-channel"]').val().trim();

        if (!name) {
            alert(rasa_messages.error_empty_channel);
            return;
        }

        sendAjax('save_channel', { name: name }, rasa_messages.success_channel_saved);
    });
});
