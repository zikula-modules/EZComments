(function ($) {
    $(document).ready(function () {
        comment.init();
    });

    var comment = {
        commentForms: {},
        currentId: 0,
        ajaxSettings: {
            dataType: 'json',
            error: this.ajaxError,
            timeout:10000
            },
        init: function () {
            //the action will be removed if JS is enabled. Otherwise it will be there.
            //so that the comment when JS is turned off
            this.cacheDom();
            this.bindEvents();
            this.getUserId();
        },
        cacheDom: function () {
            this.$comForm = $(".comment_form");
            this.$divCommentBlock = $(".itemComment");
            this.$toggleComButton = $('.fa-toggle-left');
            this.$deleteButton= $('.fa-trash');
            this.$editButton = $('.fa-pencil');
            this.$addCommentButton = this.$comForm.find('#addComment');
            this.$artId = $("input[name=artId]").attr("value");
            this.$module = $("input[name=module]").attr("value");
        },
        bindEvents: function () {
            this.$toggleComButton.on('click', this.getComments.bind(this));
            this.$addCommentButton.on('click', this.addComment.bind(this));
            this.$deleteButton.on('click', this.deleteComment.bind(this));
            this.$editButton.on('click', this.editComment.bind(this));

        },

        getUserId: function(){
            this.sendAjax('zikulaezcommentsmodule_comment_getuserid',
                {},
                {success: this.getUserIdCallback.bind(this), method: 'GET'});
        },

        getUserIdCallback: function(result, textStatus, jqXHR){
            this.userId = result.uid;
        },

        getComments: function (evt) {
            var itemName = evt.currentTarget.id;
            this.currentId = itemName.substring(8, itemName.length);

            if (evt.currentTarget.className.includes("fa-toggle-down")) {
                $('#' + itemName).removeClass("fa-toggle-down");
                $('#' + itemName).addClass("fa-toggle-left");
                this.commentForms[this.currentId].remove();
                delete this.commentForms[this.currentId];
            } else {
                $('#' + itemName).removeClass("fa-toggle-left");
                $('#' + itemName).addClass("fa-toggle-down");
                this.sendAjax('zikulaezcommentsmodule_comment_getreplies',
                    {module: this.$module, id: this.$artId, parentId: this.currentId},
                    {success: this.getCommentsCallback.bind(this), method: 'GET'});
            }
            evt.stopPropagation();
        },
        getCommentsCallback: function(result, textStatus, jqXHR){
            var commentForm = this.$comForm.clone();
            if (result) {
                var len = result.length;
                for (var i = 0; i < len; i++) {
                    var divBlock = this.$divCommentBlock.clone();
                    //Remove the arrow icon. Right now, no replies to replies
                    divBlock.find('span[id^="twiddle"]').remove();
                    //get rid of the hidden class so you can see it
                    divBlock.removeClass('hidden');
                    //enter in the text for this comment
                    divBlock.find('h3[id=itemSubject]').text(result[i].subject);
                    divBlock.find('p[id=itemComment]').text(result[i].comment);
                    divBlock.find('i[id=itemName]').text(result[i].author);
                    divBlock.attr('id', 'itemComment_' + result[i].id);
                    //add the click listener to the buttons
                    this.hookUpButtons(divBlock, result[i]);
                    //add the block to the commentForm.
                    //Is this a memory leak? No it should not be, since remove takes it all out of the DOM including events
                    commentForm.prepend(divBlock);
                }

                //add the event to the button
                this.hookUpCommentButton(commentForm, this.currentId, "c");

                commentForm.find("form").prepend("<input type=\"hidden\" name=\"parentID\" value=\"" + this.currentId + "\" />");
                commentForm.attr("id", "comment_" + this.currentId);
                var formId = "form_" + this.currentId;
                commentForm.find("form").wrap("<div id='" + formId + "'></div>");
                commentForm.addClass("ez_indent");
                $("div[id=itemChild_" + this.currentId + "]").append(commentForm);
                //push the comment form into the array for keeping
                this.commentForms[this.currentId] = commentForm;
            } else {
                commentForm.prepend('Failure!');
            }
        },

        addComment: function (evt) {
            //determine what form this is. If it is the root form
            //at the bottom, it will have a id of addComment (10 char)
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;
            var parentId = 0;
            var id = -1;
            var form;
            //we need to figure out where the form is. If it is at the bottom,
            //The form will have no addition. If it had a c, it is a child form
            //if it is a p, it is a parent form.
            if (itemLen > 10) {
                var relation = itemName.substring(11, 12);
                //if it is c, then this is a child element and we are
                //adding a comment. If it is not, then this is a parent element
                //and we are editing a comment
                if (relation === 'c'){
                    parentId = itemName.substring(13, itemLen);
                } else {
                    id = itemName.substring(13, itemLen);
                }
            }
            //get the correct form. When we pull down an arrow, the accompanying form
            //is stored in our commentForms object with a matching parentId
            //if this is the parent form (the parentId won't exist) then its already in $comForm
            var currForm = this.$comForm;
            if(parentId > 0){
                currForm = this.commentForms[parentId + 'c'];
            }
            if(id !== -1){
                currForm = this.commentForms[id + 'p'];
            }
            //Send off the data.
            this.sendAjax(
                'zikulaezcommentsmodule_comment_setcomment',
                {   module: this.$module,
                    artId: this.$artId,
                    parentID: parentId,
                    areaId: currForm.find("input[name=areaId]").attr("value"),
                    retUrl: currForm.find("input[name=retUrl]").attr("value"),
                    user: currForm.find("input[name=user]").attr("value"),
                    subject: currForm.find("input[name=subject]").val(),
                    comment: currForm.find("textarea[name=comment]").val(),
                    anonEmail: currForm.find("input[name=anonEmail]").attr("value"),
                    anonWebsite: currForm.find("input[name=anonWebsite]").attr("value"),
                    id: currForm.find("input[name=id]").attr("value")
                },
                {
                    success: this.addCommentCallback.bind(this),
                    method: 'POST',
                });
            evt.preventDefault();
            evt.stopPropagation();
        },

        addCommentCallback: function(result, textStatus, jqXHR){
            //We need to grab the data out of the response and put it in a new div block and add
            //it to the page
            var len = result.length;
            var divBlock = this.$divCommentBlock.clone();
            var subject = divBlock.find('h3[id=itemSubject]').text(result[0].subject);
            var comment = divBlock.find('p[id=itemComment]').text(result[0].comment);
            var author = divBlock.find('i[id=itemName]').text(result[0].author);
            divBlock.attr('id', 'itemComment_' + result[0].id);
            //get rid of the twiddle block. Not needed here.
            if(result[0].parentID > 0){
                divBlock.find('span[id^="twiddle"]').remove();
                divBlock.attr('id', 'itemComment_' + result[0].id);
            } else {
                //since this is the parent thread we need to
                //add an itemchild div that data can be appended to.
                divBlock.append('<div id="itemChild_' + result[0].id + '"></div>');
                var twiddle = divBlock.find('span[id^=twiddle]');
                twiddle.attr('id', 'twiddle_' + result[0].id);
                twiddle.on('click', this.getComments.bind(this));
            }
            this.hookUpButtons(divBlock, result[0]);
            divBlock.removeClass("hidden");
            var currForm = this.$comForm;
            if(result[0].parentID > 0){
                currForm = this.commentForms[result[0].parentID];
                var formText = "div[id=form_" + result[0].parentID + "]";
                currForm.find(formText).before(divBlock);
            } else {
                currForm.before(divBlock);
            }

        },
        hookUpButtons: function(target, result){
            //only add these buttons if the user id is the same
            //if this is an anonymous user, you cannot edit or trash your comments.
            if(this.userId === result.uid) {
                //modify and hook up the trash icon to all the writer to delete the comment if they want.
                var trash = target.find('span[id^=trash]');
                trash.attr('id', 'trash_' + result.id);
                trash.on('click', this.deleteComment.bind(this));

                //modify and hook up the edit icon to all the writer to delete the comment if they want.
                var edit = target.find('span[id^=edit]');
                edit.attr('id', 'edit_' + result.id);
                edit.on('click', this.editComment.bind(this));
            } else {
                //if we don't own it, then remove the icons
                target.find('span[id^=trash]').remove();
                target.find('span[id^=edit]').remove();
            }
        },

        hookUpCommentButton: function(target, id, relation){
            var comment = target.find('button[id^=addComment]');
            comment.attr('id', 'addComment_' + relation + '_' + id);
            comment.on('click', this.addComment.bind(this));

        },

        deleteComment: function (evt){
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;
            if (itemLen > 5) {
                var id = itemName.substring(6, itemLen);
                this.sendAjax(
                    'zikulaezcommentsmodule_comment_deletecomment',
                    {commentId: id, uid: this.userId},
                    {
                        success: this.deleteCommentCallback.bind(this),
                        method: 'POST',
                    });
            }

            evt.preventDefault();
            evt.stopPropagation();
        },

        deleteCommentCallback: function (result, textStatus, jqXHR){
            //if the comment was deleted from the db, then remove it from the DOM
            if(result.comdel){
                $("#itemComment_" + result.id).remove();
            }
        },

        editComment: function (evt){
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;
            var id = itemName.substring(5, itemLen);
            //grab the divBlock and grab the info that is there
            var divBlock = $('#itemComment_' + id);
            var uName = divBlock.find("i[id^=itemName]").text();
            var subject = divBlock.find("h3[id^=itemSubject]").text();
            var comment = divBlock.find("p[id^=itemComment]").text();
            //get a copy of the comment form and insert the values
            var comForm = this.$comForm.clone();
            comForm.find("input[name=user]").attr('value', uName);
            comForm.find("textarea[name=comment]").text(comment);
            comForm.find("input[name=subject]").attr('value', subject);
            this.hookUpCommentButton(comForm, id, 'p');

            var form = comForm.find('form');
            form.prepend('<input name="id" type="hidden" value="' + id + '" />');
            var itemChild = divBlock.find("div[id^=itemChild_" + id + "]");
            //remove all the old elements
            divBlock.empty();
            //insert the comForm
            divBlock.append(comForm);
            if(itemChild) {
                divBlock.append(itemChild);
            }
            this.commentForms[id] = comForm;

            evt.preventDefault();
            evt.stopPropagation();
        },

        sendAjax: function (url, data, options) {
            //push the data object into the options
            options.data = data;
            $.extend(options, this.ajaxSettings);
            var theRoute = Routing.generate(url);
            $.ajax(theRoute, options);
        },
        ajaxError: function(jqXHR, textStatus, errorThrown){

        },
    };
})(jQuery);

