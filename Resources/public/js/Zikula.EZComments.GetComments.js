
(function($){
    $(document).ready(function() {
        $('#twiddle').on('click', getData);
    });

    function getData(evt){
        $.ajax({
            url: Routing.generate('zikulaezcommentsmodule_comment_getreplies'),
            method: "GET",
            dataType: 'json',
        })
            .done(function(result){
                if(result) {
                    $("#ajaxContent").append(result.comment);
                } else {
                    $("#ajaxContnet").append('Failrue!');
                }
            })
            .fail(function(xhr, status, strErr) {
                $("#ajaxContent").append("err!" + strErr);
            })
        evt.stopPropagation();
    }
})(jQuery);