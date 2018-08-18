(function ($) {
    $(document).ready(function () {
        comment.init();
    });

    var comment = {
        currentId: 0,
        subComments: {},
        ajaxSettings: {
            dataType: "json",
            error: this.ajaxError,
            timeout: 10000,
            cache:false
        },
        init: function () {
            //the action will be removed if JS is enabled. Otherwise it will be there.
            //so that the comment when JS is turned off
            this.cacheDom();
            this.bindEvents();
            this.getUserId();
            this.$comForm.addClass("hidden");
        },
        cacheDom: function () {
            this.$comForm = $("#commentFormDiv");
            this.$currentDivBlock = null;
            this.$divCommentBlock = $(".itemComment");
            this.$toggleComButton = $(".fa-toggle-left");
            this.$deleteButton = $(".fa-trash");
            this.$editButton = $(".fa-pencil");
            this.$addCommentButton = this.$comForm.find("#addComment");
            this.$artId = $("input[name=artId]").attr("value");
            this.$module = $("input[name=module]").attr("value");
            this.$newCommentButton = $(".fa-plus");
            this.$replyButton = $(".fa-mail-reply");
        },
        bindEvents: function () {
            this.$toggleComButton.on("click", this.getComments.bind(this));
            this.$addCommentButton.on("click", this.addComment.bind(this));
            this.$deleteButton.on("click", this.deleteComment.bind(this));
            this.$editButton.on("click", this.editComment.bind(this));
            this.$newCommentButton.on("click", this.newComment.bind(this));
            this.$replyButton.on("click", this.reply.bind(this));
        },

        getUserId: function () {
            this.sendAjax("zikulaezcommentsmodule_comment_getuserid",
                {},
                {success: this.getUserIdCallback.bind(this), method: "GET"});
        },

        getUserIdCallback: function (result, textStatus, jqXHR) {
            this.userId = result.uid;
        },

        getComments: function (evt) {
            var itemName = evt.currentTarget.id;
            this.currentId = itemName.substring(8, itemName.length);
            if (evt.currentTarget.className.includes("fa-toggle-down")) {
                $("#" + itemName).removeClass("fa-toggle-down");
                $("#" + itemName).addClass("fa-toggle-left");
                var theComments = this.subComments[this.currentId];
                if (theComments) {
                    theComments.remove();
                    delete this.subComments[this.currentId];
                }
                this.clearAndHideForm();
            } else {
                $("#" + itemName).removeClass("fa-toggle-left");
                $("#" + itemName).addClass("fa-toggle-down");
                this.sendAjax("zikulaezcommentsmodule_comment_getreplies",
                    {module: this.$module, id: this.$artId, parentId: this.currentId},
                    {success: this.getCommentsCallback.bind(this), method: "GET"});
            }
            evt.stopPropagation();
        },
        getCommentsCallback: function (result, textStatus, jqXHR) {
            if (result) {
                //get all the comments and add them to a form.
                var len = result.length;
                if (len === 0) {
                    //no comments to add. Change the toggle back and exit
                    //we recorded the ID in getComments
                    var itemName = "twiddle_" + this.currentId;
                    var toggleToChange = $("#" + itemName);
                    toggleToChange.removeClass("fa-toggle-down");
                    toggleToChange.addClass("fa-toggle-left");
                    var noReplyDiv = $("#no_replies");
                    noReplyDiv.removeClass("hidden");
                    var blockAfter = $("#itemComment_" + this.currentId);
                    $(noReplyDiv).insertAfter(blockAfter);
                    noReplyDiv.fadeIn();
                    noReplyDiv.delay(2000);
                    noReplyDiv.fadeOut();
                    return;
                }
                this.addSubComments(result, len);
            }
        },

        addComment: function (evt) {
            //determine what form this is. If it is the root form
            //at the bottom, it will have a id of addComment (10 char)
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;

            //we need to figure out where the form is. If it is at the bottom,
            //The form will have no addition.
            if (itemLen > 10) {
                this.currentId = itemName.substring(11, itemLen);
            } else {
                this.currentId = 0;
            }

            var currForm = this.$comForm;
            //if we have an id, we are doing an edit. Otherwise this is a reply
            //or it is a top-level comment
            if (this.currentId === -1) {
                this.currentId = 0;
            }

            var parentId = currForm.find("input[name=parentID]").attr("value");

            //Send off the data.
            this.sendAjax(
                "zikulaezcommentsmodule_comment_setcomment",
                {
                    module: this.$module,
                    artId: this.$artId,
                    parentID: parentId,
                    areaId: currForm.find("input[name=areaId]").attr("value"),
                    retUrl: currForm.find("input[name=retUrl]").attr("value"),
                    user: currForm.find("input[name=user]").attr("value"),
                    subject: currForm.find("input[name=subject]").val(),
                    comment: currForm.find("textarea[name=comment]").val(),
                    anonEmail: currForm.find("input[name=anonEmail]").attr("value"),
                    anonWebsite: currForm.find("input[name=anonWebsite]").attr("value"),
                    id: this.currentId
                },
                {
                    success: this.addCommentCallback.bind(this),
                    method: "POST"
                });
            evt.preventDefault();
            evt.stopPropagation();
        },

        addCommentCallback: function (result, textStatus, jqXHR) {
            //We need to grab the data out of the response and put it in a new div block and add
            //it to the page
            var divBlock;
            if (result[0].isEdit) {
                var id = result[0].id;
                divBlock = $("#itemComment_" + id);
                //enter in the changed values
                divBlock.find("h3[id^=itemSubject_" + id + "]").text(result[0].subject);
                divBlock.find("p[id^=itemComment_" + id + "]").text(result[0].comment);
                divBlock.find("i[id^=itemName_" + id + "]").text(result[0].author);

            } else {
                divBlock = this.$divCommentBlock.clone();
                //enter in the changed values
                divBlock.find("h3[id^=itemSubject]").text(result[0].subject);
                divBlock.find("p[id^=itemComment]").text(result[0].comment);
                divBlock.find("i[id^=itemName]").text(result[0].author);
                divBlock.attr("id", "itemComment_" + result[0].id);
            }

            //get rid of the twiddle block. Not needed here.
            if (result[0].parentID > 0) {
                divBlock.find("span[id^=twiddle]").remove();
                divBlock.attr("id", "itemComment_" + result[0].id);
            } else if (!result[0].isEdit) {
                //since this is the parent thread we need to
                //add an itemchild div that data can be appended to.
                divBlock.append("<div id=\"itemChild_" + result[0].id + "\"></div>");
                var twiddle = divBlock.find("span[id^=twiddle]");
                twiddle.attr("id", "twiddle_" + result[0].id);
                twiddle.on("click", this.getComments.bind(this));
                //this is the root comment form we re using. Therefore, we are adding the comment
                //and need to put back the add comment button for the next comment
                this.$newCommentButton.removeClass("hidden");
            }
            this.hookUpButtons(divBlock, result[0]);

            var currForm = this.$comForm;
            if (result[0].parentID > 0) {
                var subComments = this.subComments[result[0].parentID];
                //if subcomments are already present, just place it at the end.
                if(!subComments){
                    subComments = $("<div id=subComments_" + result[0].parentID + "></div>");
                    subComments.addClass("ez_indent");
                }
                //if this is a new comment put it at the end
                if(!result[0].isEdit){
                    //this is a subcomment, so get rid of the reply twiddle
                    divBlock.find("p[id=replyPara]").remove();
                    subComments.append(divBlock);
                    //update the subcomments we have stored.
                    this.subComments[result[0].parentID] = subComments;
                }
                currForm.before(subComments);
            } else {
                currForm.before(divBlock);
            }
            divBlock.removeClass("hidden");
            this.clearAndHideForm();
        },
        hookUpButtons: function (target, result) {
            //only add these buttons if the user id is the same
            //if this is an anonymous user, you cannot edit or trash your comments.
            if (this.userId === result.uid) {
                //modify and hook up the trash icon to all the writer to delete the comment if they want.
                var trash = target.find("span[id^=trash]");
                trash.attr("id", "trash_" + result.id);
                trash.on("click", this.deleteComment.bind(this));

                //modify and hook up the edit icon to all the writer to delete the comment if they want.
                var edit = target.find("span[id^=edit]");
                edit.attr("id", "edit_" + result.id);
                edit.on("click", this.editComment.bind(this));
            } else {
                //if we don't own it, then remove the icons
                target.find("span[id^=trash]").remove();
                target.find("span[id^=edit]").remove();
            }
        },

        hookUpCommentButton: function (target, id, parentId) {
            target.find("button[id^=addComment]").attr("id", "addComment_" + id);
            target.find("input[name=parentID]").val(parentId);
        },

        clearAndHideForm: function(){
            var currForm = this.$comForm;
            currForm.find("textarea").val("");
            currForm.find("input[name=subject]").val("");
            currForm.addClass("hidden");
        },


        deleteComment: function (evt) {
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;
            if (itemLen > 5) {
                var id = itemName.substring(6, itemLen);
                this.sendAjax(
                    "zikulaezcommentsmodule_comment_deletecomment",
                    {commentId: id, uid: this.userId},
                    {
                        success: this.deleteCommentCallback.bind(this),
                        method: "POST"
                    });
            }

            evt.preventDefault();
            evt.stopPropagation();
        },

        deleteCommentCallback: function (result, textStatus, jqXHR) {
            //if the comment was deleted from the db, then remove it from the DOM
            if (result.comdel) {
                var commentsToDelete = $("#itemComment_" + result.id);
                commentsToDelete.remove();
            }
        },

        editComment: function (evt) {
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;
            var id = itemName.substring(5, itemLen);
            //grab the divBlock and grab the info that is there
            var divBlock = $("#itemComment_" + id);
            var uName = divBlock.find("i[id^=itemName_" + id + "]").text();
            var subject = divBlock.find("h3[id^=itemSubject_" + id + "]").text();
            var comment = divBlock.find("p[id^=itemComment_" + id + "]").text();

            //get a copy of the comment form and insert the values
            var comForm = this.$comForm;
            //var commentDiv = comForm.find("div[id^=comment_]");
            //commentDiv.attr("id", "comment_" + id);
            comForm.find("input[name=user]").val(uName);
            comForm.find("textarea[name=comment]").val(comment);
            comForm.find("input[name=subject]").val(subject);

            var form = comForm.find("form");
            form.attr("id", id);
            //hide the div block
            divBlock.addClass("hidden");
            //insert the comForm into the appropriate place.
            divBlock.after(comForm);
            //I have to check to see if a comment is already entered and if so reveal it.
            if(this.$currentDivBlock !== null) {
                var currDivBlockIdString = (this.$currentDivBlock).attr("id");
                var itemLen2 = currDivBlockIdString.length;
                var idCurDiv = currDivBlockIdString.substring(12, itemLen2);
                if (idCurDiv !== id) {
                    this.$currentDivBlock.removeClass("hidden");
                }
            }
            var parentID = 0;
            this.$currentDivBlock = divBlock;
            var itemChild = divBlock.find("div[id^=itemChild_" + id + "]");
            if (itemChild.length === 0) {
                var parentName = divBlock.parent().attr("id");
                parentID = parentName.substring(12, parentName.length);
                //if there is no itemchild, then this is a subthread and we need to mark the parentID
                form.find("input[name=parentID]").val(parentID);
            } else {
                //The comment form may have been ready to add a root comment
                //Just in case that is true, we show the comment button.
                //It doesn't hurt if it is already visible.
                this.$newCommentButton.removeClass("hidden");
            }
            this.hookUpCommentButton(comForm, id, parentID);

            //finally show the form
            comForm.removeClass("hidden");

            evt.preventDefault();
            evt.stopPropagation();
        },

        newComment: function (evt) {
            //we cannot cache this DOM because it can change.
            //get the last comment
            var lastComment = $("div[id^=itemComment_]").last();
            //clean out the comForm if there is anything in it
            this.clearAndHideForm();
            this.hookUpCommentButton(this.$comForm, 0, 0);
            lastComment.after(this.$comForm);
            this.$comForm.removeClass("hidden");
            this.$newCommentButton.addClass("hidden");
        },

        reply: function (evt) {
            //find the target comment
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;
            this.currentId = itemName.substring(6, itemLen);
            //are the comments already showing?
            var subComments = this.subComments[this.currentId];
            if(subComments) {
                var target = $("#itemChild_" + this.currentId);
                this.finishReplySetup(target);
            } else {
                this.sendAjax("zikulaezcommentsmodule_comment_getreplies",
                    {module: this.$module, id: this.$artId, parentId: this.currentId},
                    {success: this.replyCallback.bind(this), method: "GET"});
            }

        },

        replyCallback: function (result, textStatus, jqXHR) {
            if (result) {
                //change the toggle
                var itemName = "twiddle_" + this.currentId;
                $("#" + itemName).removeClass("fa-toggle-left");
                $("#" + itemName).addClass("fa-toggle-down");
                //get all the comments and add them to a form.
                var len = result.length;
                if (len !== 0) {
                    this.addSubComments(result, len);
                }
                var target = $("#itemChild_" + this.currentId);
                this.finishReplySetup(target);
            }
        },

        addSubComments: function(result, len) {
            var subComments = $("<div id=subComments_" + this.currentId + "></div>");
            for (var i = 0; i < len; i++) {
                var divBlock = this.$divCommentBlock.clone();
                //Remove the arrow icon. Right now, no replies to replies
                divBlock.find("span[id^=twiddle]").remove();
                divBlock.find("span[id^=reply]").remove();
                //get rid of the hidden class so you can see it
                divBlock.removeClass("hidden");
                //enter in the text for this comment
                divBlock.find("h3[id=itemSubject]").text(result[i].subject);
                divBlock.find("p[id=itemComment]").text(result[i].comment);
                divBlock.find("i[id=itemName]").text(result[i].author);
                divBlock.attr("id", "itemComment_" + result[i].id);
                //add the click listener to the buttons
                this.hookUpButtons(divBlock, result[i]);
                //add the block to the commentForm.
                //Is this a memory leak? No it should not be, since remove takes it all out of the DOM including events
                subComments.prepend(divBlock);
            }
            subComments.addClass("ez_indent");
            //remember this set of objects for deletion when the twiddle box is closed
            this.subComments[this.currentId] = subComments;
            //finally add the comments to the right spot in the page
            $("div[id=itemChild_" + this.currentId + "]").append(subComments);
        },


        finishReplySetup: function (target) {
            //get the comment form and get it ready
            var comForm = this.$comForm;
            //this is a new comment, 0 it out.
            this.hookUpCommentButton(comForm, 0, this.currentId);

            //enter the form after the comment
            target.append(comForm);
            //indent since this is a reply
            comForm.addClass("ez_indent");
            //finally show the form
            comForm.removeClass("hidden");
            //The comment form may have been ready to add a root comment
            //Just in case that is true, we show the comment button.
            //It doesn't hurt if it is already visible.
            this.$newCommentButton.removeClass("hidden");
        },

        sendAjax: function (url, data, options) {
            //push the data object into the options
            options.data = data;
            $.extend(options, this.ajaxSettings);
            var theRoute = Routing.generate(url);
            $.ajax(theRoute, options);
        },
        ajaxError: function (jqXHR, textStatus, errorThrown) {
            window.alert(textStatus + "\n" + errorThrown);
        },
    };
})(jQuery);

