
(function($){
    $(document).ready(function(){
        $.post(
            Routing.generate('zikulaezcommentsmodule_comment_getreplies'),
            {comment_id: 3},
            successFn(),
            "json"
        );
    });

    function successFn(result) {
        var jsonResults = JSON.parse(result);
        $("#ajaxContent").append(jsonResults.comment);
    }

    function errorFn(xhr, status, strErr) {
        alert("err!" + strErr);
    }
})(jQuery);