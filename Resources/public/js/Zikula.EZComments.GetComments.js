
(function($){
    $(document).ready(function() {
        $('.fa-toggle-left').on('click', getComments);
    });

    function getComments(evt){
        var itemName = $(this).prop('id');
        var itemID = itemName.substring(8, itemName.length);

        if($(this).hasClass("fa-toggle-down")){
            var theText = $("div[id=comment_" + itemID + "]").html();
            $(this).removeClass("fa-toggle-down");
            $(this).addClass("fa-toggle-left");
        } else {
            $(this).removeClass("fa-toggle-left");
            $(this).addClass("fa-toggle-down");
            var commentForm = $(".comment_form").clone();
            var artId = $("input[name=artId]").attr("value");
            var module = $("input[name=module]").attr("value");
            //grab the replies that match this comment
            $.getJSON(
                Routing.generate('zikulaezcommentsmodule_comment_getreplies'),
                {module: module, id: artId, parentId: itemID})

                .done(function(result){
                    if(result) {
                        var len = result.length;
                        for(var i = 0; i < len; i++){
                            var divID = "div[id=itemComment_" + itemID + "]";
                            //grab a copy of the comment block for population
                            var divBlock = $(divID).clone();
                            //change the id attr of this block to match the incoming comment
                            divBlock.find('h3[id^="itemSubject_"]').attr('id', "itemSubject_" + result[i].id);
                            divBlock.find('p[id^="itemComment_"]').attr('id', "itemComment_" + result[i].id);
                            divBlock.find('i[id^="itemName_"]').attr('id', "itemName_" + result[i].id);
                            divBlock.find('span[id^="twiddle_"]').remove();
                            commentForm.prepend(divBlock);
                            commentForm.find("div[id^=itemComment_" + itemID + "]").wrap("<blockquote></blockquote>");
                            commentForm.find("form").prepend("<input type=\"hidden\" name=\"parentID\" value=\"" + itemID + "\" />");
                            commentForm.attr("id", "comment_" + itemID);
                            commentForm.find("form").wrap("<blockquote></blockquote>");
                        }
                        $("div[id=itemComment_" + itemID + "]").append(commentForm.html());
                    } else {
                        commentForm.prepend('Failure!');
                    }
                })
                .fail(function(xhr, status, strErr) {
                    commentForm.append("err!" + strErr);
                });

        }
        /*$.ajax({
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
            })*/
        /**/


        evt.stopPropagation();
    }
})(jQuery);