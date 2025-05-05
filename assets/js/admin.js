jQuery(document).ready(function($) {
    function sendAjax(action, data, successMessage) {
        data.action = action;
        data._ajax_nonce = rasa_ajax_object.nonce;

        $.post(rasa_ajax_object.ajax_url, data)
            .done(function(response) {
                if (response.success) {
                    alert(response.data.message || successMessage);
                } else {
                    alert(response.data.message || 'خطایی رخ داده است.');
                }
            })
            .fail(function() {
                alert('خطایی در ارتباط با سرور رخ داده است.');
            });
    }

    $('.btn-save').on('click', function(e) {
        e.preventDefault();
        const apiKey = $('input[name="api-key"]').val().trim();

        if (!apiKey) {
            alert('لطفاً کلید API را وارد کنید.');
            return;
        }

        sendAjax('save_api_key', { api_key: apiKey }, 'کلید API با موفقیت ذخیره شد.');
    });

    $('.btn-save-channel').on('click', function(e) {
        e.preventDefault();
        const name = $('input[name="name-channel"]').val().trim();

        if (!name) {
            alert('لطفاً نام کانال را وارد کنید.');
            return;
        }

        sendAjax('save_channel', { name: name }, 'کانال با موفقیت ذخیره شد.');
    });
});
