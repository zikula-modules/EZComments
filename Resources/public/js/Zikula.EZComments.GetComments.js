
(function($){
    $(document).ready(function() {
        $('.fa-toggle-left').on('click', getComments);
        $('#addComment').on('click', setComment);
        //the action will be removed is JS is enabled. Otherwise it will be there.
        $('#commentForm').attr('action', "nope");
    });

    function getComments(evt){
        var itemName = $(this).prop('id');
        var itemID = itemName.substring(8, itemName.length);

        if($(this).hasClass("fa-toggle-down")){
            $("div[id=itemChild_" + itemID + "]").empty();
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
                            var subId = "itemSubject_" + result[i].id;
                            divBlock.find('h3[id^="itemSubject_"]').attr('id', subId);
                            //update the html to the subject
                            divBlock.find('h3[id=' + subId + ']').html(result[i].subject);
                            //update comment block id and insert the comment
                            var itemComm = "itemComment_" + result[i].id;
                            divBlock.find('p[id^="itemComment_"]').attr('id', itemComm);
                            divBlock.find('p[id=' + itemComm + ']').html(result[i].comment);
                            //update the name field
                            var itemName = "itemName_" + result[i].id;
                            divBlock.find('i[id^="itemName_"]').attr('id', itemName);
                            divBlock.find('i[id=' + itemName + ']').html(result[i].author);
                            //get rid of the twiddle block. Not needed here.
                            divBlock.find('span[id^="twiddle_"]').remove();
                            divBlock.attr('id', 'itemComment_' + result[i].id);
                            commentForm.prepend(divBlock);
                            //I need to add style info for the blockquote, that is why it is weird. Or change it to a div and have it indented (better)
                            commentForm.find("div[id^=itemComment_" + result[i].id + "]").wrap("<div class='ez_indent'></div>");
                        }
                        var comButton = commentForm.find("button[id=addComment]");
                        comButton.on('click', setComment)
                        comButton.attr('id', "addComment_" + itemID);
                        commentForm.find("form").prepend("<input type=\"hidden\" name=\"parentID\" value=\"" + itemID + "\" />");
                        commentForm.attr("id", "comment_" + itemID);
                        commentForm.find("form").wrap("<div class='ez_indent'></div>");
                        $("div[id=itemChild_" + itemID + "]").append(commentForm.html());
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

    function setComment(evt){
        //determine what form this is. If it is the root form
        //at the bottom, it will have a id of addComment (10 char)
        //if it is a child form, it will have more and we can pull the ID of the parent out.
        var itemName = $(this).prop('id');
        var itemLen = itemName.length
        var parentId = 0;
        var form;
        if(itemLen > 10){
            parentId = itemName.substring(11, itemLen);
            form = $("#commentForm_" + parentId);
        } else {
            form = $("#commentForm");
        }
        //grab the needed form values
        var artId = form.find("input[name=artId]").attr("value");
        var module = form.find("input[name=module]").attr("value");
        var areaId = form.find("input[name=areaId]").attr("value");
        var retUrl = form.find("input[name=retUrl]").attr("value");
        var user = form.find("input[name=user]").attr("value");
        var subject = form.find("input[name=subject]").attr("value");
        var comment = form.find("input[name=comment]").attr("value");
        var anonEmail = form.find("input[name=anonEmail]").attr("value");
        var anonWebsite = form.find("input[name=anonWebsite]").attr("value");

        $.post(
            Routing.generate('zikulaezcommentsmodule_comment_setcomment'),
            {   module: module,
                id: artId,
                parentId: parentId,
                areaId: areaId,
                retUrl: retUrl,
                user: user,
                subject: subject,
                comment: comment,
                anonEmail: anonEmail,
                anonWebsite: anonWebsite
            })

            .done(function(result){
                if(result) {
                    var len = result.length;

                } else {

                }
            })
            .fail(function(xhr, status, strErr) {
                commentForm.append("err!" + strErr);
            });
        evt.stopPropagation();
    }
})(jQuery);