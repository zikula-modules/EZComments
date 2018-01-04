
(function($){
    $('#twiddle').on('click', getData);

    function getData(evt){
        $.get(
            Routing.generate('zikulaezcommentsmodule_comment_getreplies'),
            successFn(),
            "json"
        );
        evt.stopPropagation();
    }
    function successFn(result) {
        var jsonResults = '';
        if(result){
             jsonResults = JSON.parse(result);
        }
        $("#ajaxContent").append(jsonResults.comment);
    }

    function errorFn(xhr, status, strErr) {
        $("#ajaxContent").append("err!" + strErr);
    }
})(jQuery);