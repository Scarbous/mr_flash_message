(function ($) {

    function mrfmShowMessages() {
        if (flashmessages) {
            $.each(flashmessages, function (index, message) {
                $.notify(
                    message.message,
                    {
                        "type": message.type
                    }
                );
            });
        }
    }

    $(window)
        .ready(mrfmShowMessages);
})(jQuery);
