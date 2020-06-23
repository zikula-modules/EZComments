(function ($) {
    $(document).ready(function () {
        comment.init();
    });

    var comment = {
        currentId: 0,
        $replySubject: '',
        subComments: {},
        ajaxSettings: {
            dataType: 'json',
            error: this.ajaxError,
            timeout: 10000,
            cache: false
        },
        init: function () {
            //the action will be removed if JS is enabled. Otherwise it will be there.
            //so that the comment when JS is turned off
            this.cacheDom();
            this.bindEvents();
            this.getUserId();
            this.$comForm.addClass('d-none');
            this.setUpMessageDialog();
        },
        cacheDom: function () {
            this.$comForm = $('#commentFormDiv');
            this.$currentDivBlock = null;
            this.$divCommentBlock = $('.itemComment');
            this.$toggleComButton = $('.fa-caret-square-left');
            this.$deleteButton = $('.fa-trash-alt');
            this.$editButton = $('.fa-pencil-alt');
            this.$addCommentButton = $('#addComment');
            this.$artId = $('input[name=artId]').val();
            this.$module = $('input[name=module]').val();
            this.$newCommentButton = $('.fa-plus');
            this.$replyButton = $('.fa-reply');
            this.$cancelButton = $('#cancelComment');
        },
        bindEvents: function () {
            this.$toggleComButton.on('click', this.getComments.bind(this));
            this.$addCommentButton.on('click', this.addComment.bind(this));
            this.$deleteButton.on('click', this.deleteComment.bind(this));
            this.$editButton.on('click', this.editComment.bind(this));
            this.$newCommentButton.on('click', this.newComment.bind(this));
            this.$replyButton.on('click', this.reply.bind(this));
            this.$cancelButton.on('click', this.cancelComment.bind(this));
        },

        getUserId: function () {
            this.sendAjax(
                'zikulaezcommentsmodule_comment_getuserid',
                {},
                {
                    method: 'GET',
                    success: this.getUserIdCallback.bind(this)
                }
            );
        },

        setUpMessageDialog: function(){
            $('#dialog-message').dialog({
                autoOpen: false,
                buttons: {
                    Ok: function() {
                        $(this).dialog('close');
                    }
                },
                width: 450,
                open: function(event, ui)
                {
                    setTimeout(function() {
                        $('#dialog-message').dialog('close');
                    }, 5000);
                },
                show: {
                    effect: 'blind',
                    duration: 1000
                },
                hide: {
                    effect: 'fade',
                    duration: 1000
                }
            });
        },

        getUserIdCallback: function (result, textStatus, jqXHR) {
            this.userId = result.uid;
        },

        getComments: function (evt) {
            var itemName = evt.currentTarget.id;
            this.currentId = itemName.substring(8, itemName.length);
            if (evt.currentTarget.className.includes('fa-caret-square-down')) {
                $('#' + itemName).removeClass('fa-caret-square-down');
                $('#' + itemName).addClass('fa-caret-square-left');
                var theComments = this.subComments[this.currentId];
                if (theComments) {
                    theComments.remove();
                    delete this.subComments[this.currentId];
                }
                this.clearAndHideForm();
            } else {
                $('#' + itemName).removeClass('fa-caret-square-left');
                $('#' + itemName).addClass('fa-caret-square-down');
                this.sendAjax(
                    'zikulaezcommentsmodule_comment_getreplies',
                    {
                        module: this.$module,
                        id: this.$artId,
                        parentId: this.currentId
                    },
                    {
                        method: 'GET',
                        success: this.getCommentsCallback.bind(this)
                    }
                );
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
                    var itemName = 'twiddle_' + this.currentId;
                    var toggleToChange = $('#' + itemName);
                    toggleToChange.removeClass('fa-caret-square-down');
                    toggleToChange.addClass('fa-caret-square-left');
                    var noReplyDiv = $('#no_replies');
                    noReplyDiv.html(Translator.trans('There are no replies to this comment.'));
                    noReplyDiv.removeClass('d-none');
                    var blockAfter = $('#itemComment_' + this.currentId);
                    $(noReplyDiv).insertAfter(blockAfter);
                    noReplyDiv.fadeIn();
                    noReplyDiv.delay(1000);
                    noReplyDiv.fadeOut();
                    return;
                }
                this.addSubComments(result, len);
            }
        },

        gatherCommentInfo: function (evt, lenOfButtonText) {
            //determine what form this is. If it is the root form
            //at the bottom, it will have a id of addComment (10 char)
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;

            //we need to figure out where the form is. If it is at the bottom,
            //The form will have no addition.
            if (itemLen > lenOfButtonText) {
                this.currentId = itemName.substring(lenOfButtonText + 1, itemLen);
            } else {
                this.currentId = 0;
            }
            //if we have an id, we are doing an edit. Otherwise this is a reply
            //or it is a top-level comment
            if (this.currentId === -1) {
                this.currentId = 0;
            }
        },

        addComment: function (evt) {

            this.gatherCommentInfo(evt, 10);
            var currForm = this.$comForm;
            var parentId = currForm.find('input[name=parentID]').val();
            var subject = currForm.find('input[name=subject]').val();
            var comment = currForm.find('textarea[name=comment]').val();

            // send off the data
            this.sendAjax(
                'zikulaezcommentsmodule_comment_verifycomment',
                {
                    module: this.$module,
                    artId: this.$artId,
                    parentID: parentId,
                    areaId: currForm.find('input[name=areaId]').val(),
                    retUrl: currForm.find('input[name=retUrl]').val(),
                    user: currForm.find('input[name=user]').val(),
                    subject: subject,
                    comment: comment,
                    anonEmail: currForm.find('input[name=anonEmail]').val(),
                    anonWebsite: currForm.find('input[name=anonWebsite]').val(),
                    id: this.currentId
                },
                {
                    method: 'POST',
                    success: this.verifiedCommentCallback.bind(this)
                }
            );
            evt.preventDefault();
            evt.stopPropagation();
        },

        verifiedCommentCallback: function(result, textStatus, jsXHR) {
            if(result.verified === false){
                //There was a problem, post the message that came back and exit
                $('#comment_message').text(result.message);
                $('#dialog-message').dialog('open');
                switch(result.reason){
                    case 'comment':
                        $('#comment-area').css('border-color', '#f34f4f');
                        $('#comment-area').css('border-width', '1px');
                        $('#comment-area').css('background', '#ffe1da');
                        break;
                    case 'subject':
                        $('#subject-area').css('border-color', '#f34f4f');
                        $('#subject-area').css('border-width', '1px');
                        $('#subject-area').css('border-style', 'solid');
                        $('#subject-area').css('background', '#ffe1da');
                        break;
                }
                setTimeout(this.resetCommentFields.bind(this), 5000);
                return;
            }

            this.sendAjax(
                'zikulaezcommentsmodule_comment_setcomment',
                result,
                {
                    method: 'POST',
                    success: this.addCommentCallback.bind(this)
                }
            );
        },

        resetCommentFields: function(){
            $('#subject-area, #comment-area').css('border-color', 'black');
            $('#subject-area, #comment-area').css('border-width', '1px');
            $('#subject-area, #comment-area').css('background', 'white');
            $('#subject-area').css('border-style', 'solid');
        },

        addCommentCallback: function (result, textStatus, jqXHR) {
            var divBlock;
            if (result.isEdit) {
                var id = result.id;
                divBlock = $('#itemComment_' + id);
                //enter in the changed values
                divBlock.find('h3[id^=itemSubject_' + id + ']').text(result.subject);
                divBlock.find('p[id^=itemComment_' + id + ']').html(result.comment);
                divBlock.find('i[id^=itemName_' + id + ']').text(result.author);
                divBlock.removeClass('d-none');
            } else {
                divBlock = this.$divCommentBlock.clone();
                //enter in the changed values
                this.fillDivBlock(divBlock, result);
            }
            //get rid of the twiddle block. Not needed here.
            if (result.parentID > 0) {
                divBlock.find('span[id^=twiddle]').remove();
                divBlock.attr('id', 'itemComment_' + result.id);
            } else if (!result.isEdit) {
                //since this is the parent thread we need to
                //add an itemchild div that data can be appended to.
                divBlock.append('<div id="itemChild_' + result.id + '"></div>');
                var twiddle = divBlock.find('span[id^=twiddle]');
                twiddle.attr('id', 'twiddle_' + result.id);
                twiddle.on('click', this.getComments.bind(this));
                //this is the root comment form we re using. Therefore, we are adding the comment
                //and need to put back the add comment button for the next comment
                this.$newCommentButton.removeClass('d-none');
            }
            this.hookUpButtons(divBlock, result);

            var currForm = this.$comForm;
            //Is this a subcomment?
            if (result.parentID > 0) {
                var subComments = this.subComments[result.parentID];
                //if There are not subcomments, then create the div they live in and add the ml-4 class to make sure it's indented.
                if(!subComments){
                    subComments = $('<div id="subComments_' + result.parentID + '"></div>');
                    subComments.addClass('ml-4');
                    //There were no subcomments to this comment.
                    //we need to add this to the DOM since it wasn't there, right after the <div id="itemchild_"> tag.
                    $('#itemChild_' + result.parentID).prepend(subComments);
                }
                //if this is a new comment put it at the end
                if(!result.isEdit){
                    var replyIcon = divBlock.find('span[id^=reply]');
                    replyIcon.attr('id', 'reply_' + result.parentID);
                    replyIcon.on('click', this.reply.bind(this));
                    subComments.append(divBlock);
                    //update the subcomments we have stored.
                    this.subComments[result.parentID] = subComments;
                } else {
                    //This is an edit. We need to fill the hidden block with the
                    //new values and reveal it
                    this.fillDivBlock(divBlock, result);
                }
            } else {
                //This is on the main thread. I need to modify the reply block at least
                var replyButton = divBlock.find('span[id=reply]');
                replyButton.attr('id', 'reply_' + result.id);
                replyButton.on('click', this.reply.bind(this));
                currForm.before(divBlock);
            }
            this.clearAndHideForm();
        },
        hookUpButtons: function (target, result) {
            //only add these buttons if the user id is the same
            //if this is an anonymous user, you cannot edit or trash your comments.
            if (this.userId === result.uid) {
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

        hookUpCommentButton: function (target, id, parentId) {
            target.find('button[id^=addComment]').attr('id', 'addComment_' + id);
            target.find('input[name=parentID]').val(parentId);
        },

        clearAndHideForm: function() {
            var currForm = this.$comForm;
            currForm.find('textarea').val('');
            currForm.find('input[name=subject]').val('');
            currForm.addClass('d-none');
        },

        cancelComment :function(evt) {
            //Find out the currentId
            this.gatherCommentInfo(evt, 13);
            //show the comment again. We are not changing anything tho
            if(this.currentId === 0){
                this.$newCommentButton.removeClass('d-none');
            }
            //If we have a divBlock hidden, we want to reveal it
            if(this.$currentDivBlock !== null){
                this.$currentDivBlock.removeClass('d-none');
                //reset to null since the divBlock is no longer hidden
                this.$currentDivBlock = null;
            }

            //hide the form.
            this.clearAndHideForm();

            //stop propagation of this event
            evt.preventDefault();
            evt.stopPropagation();
        },

        deleteComment: function (evt) {
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;
            if (itemLen > 5) {
                var id = itemName.substring(6, itemLen);
                this.sendAjax(
                    'zikulaezcommentsmodule_comment_deletecomment',
                    {
                        commentId: id,
                        uid: this.userId
                    },
                    {
                        method: 'POST',
                        success: this.deleteCommentCallback.bind(this)
                    }
                );
            }

            evt.preventDefault();
            evt.stopPropagation();
        },

        deleteCommentCallback: function (result, textStatus, jqXHR) {
            //if the comment was deleted from the db, then remove it from the DOM
            if (result.comdel) {
                var commentsToDelete = $('#itemComment_' + result.id);
                commentsToDelete.remove();
                if(result.parentid > 0){
                    //This is a signal that there are no other replies. Hide that twiddle
                    var itemName = '#twiddle_' + result.parentid;
                    $(itemName).addClass('d-none');
                }
            }
        },

        editComment: function (evt) {
            var itemName = evt.currentTarget.id;
            var itemLen = itemName.length;
            var id = itemName.substring(5, itemLen);
            //grab the divBlock and grab the info that is there
            var divBlock = $('#itemComment_' + id);
            var uName = divBlock.find('i[id^=itemName_' + id + ']').text();
            var subject = divBlock.find('h3[id^=itemSubject_' + id + ']').text();
            var comment = divBlock.find('p[id^=itemComment_' + id + ']').text();
            comment.replace(/<br\s*\/?>/mg, "\n");

            //get a copy of the comment form and insert the values
            var comForm = this.$comForm;
            var commentDiv = comForm.find('div[id^=comment_]');
            commentDiv.attr('id', 'comment_' + id);
            comForm.find('input[name=user]').val(uName);
            comForm.find('textarea[name=comment]').val(comment);
            comForm.find('input[name=subject]').val(subject);

            var form = comForm.find('form');
            form.attr('id', id);
            //hide the div block
            divBlock.addClass('d-none');
            //insert the comForm into the appropriate place.
            divBlock.after(comForm);
            //I have to check to see if a comment is already entered and if so reveal it.
            if(this.$currentDivBlock !== null) {
                var currDivBlockIdString = (this.$currentDivBlock).attr('id');
                var itemLen2 = currDivBlockIdString.length;
                var idCurDiv = currDivBlockIdString.substring(12, itemLen2);
                if (idCurDiv !== id) {
                    this.$currentDivBlock.removeClass('d-none');
                }
            }
            var parentID = 0;
            this.$currentDivBlock = divBlock;
            var itemChild = divBlock.find('div[id^=itemChild_' + id + ']');
            if (itemChild.length === 0) {
                var parentName = divBlock.parent().attr('id');
                parentID = parentName.substring(12, parentName.length);
                //if there is no itemchild, then this is a subthread and we need to mark the parentID
                form.find('input[name=parentID]').val(parentID);
            } else {
                //The comment form may have been ready to add a root comment
                //Just in case that is true, we show the comment button.
                //It doesn't hurt if it is already visible.
                this.$newCommentButton.removeClass('d-none');
            }
            this.hookUpCommentButton(comForm, id, parentID);

            //finally show the form
            comForm.removeClass('d-none');

            evt.preventDefault();
            evt.stopPropagation();
        },

        newComment: function (evt) {
            //we cannot cache this DOM because it can change.
            //get the last comment
            var lastComment = this.getLastRootComment();
            //clean out the comForm if there is anything in it
            this.clearAndHideForm();
            this.hookUpCommentButton(this.$comForm, 0, 0);
            lastComment.after(this.$comForm);
            this.$comForm.removeClass('d-none');
            //since this is a new comment, we don't want the comment box indented.
            this.$comForm.removeClass('ml-4')
            this.$newCommentButton.addClass('d-none');
            //since this is a new comment, the id is 0
            this.currentId = 0;
        },

        getLastRootComment: function(){
          var lastComment = $('div[id^=itemComment_]').last();
          if(lastComment.length === 0){
              return lastComment;
          }
          var theParent = lastComment.parent();
          //walk the hierarchy until you get to a root comment
          while(theParent.attr('id') !== 'Comments') {
              lastComment = theParent;
              theParent = lastComment.parent();
          }
          return lastComment;
        },

        reply: function (evt) {
            //find the target comment
            var itemName = evt.currentTarget.id;
            //get the subject of the item you are replying to
            var items = itemName.split('_');
            this.currentId = items[1];
            //if this is a root comment, then get its subject
            if(items.length === 2){
                this.$replySubject = $('#itemSubject_' + items[1]).text();
            } else {
                this.$replySubject = $('#itemSubject_' + items[2]).text();
            }
            //are the comments already showing?
            var subComments = this.subComments[this.currentId];
            if(subComments) {
                var target = $('#itemChild_' + this.currentId);
                this.finishReplySetup(target);
            } else {
                this.sendAjax(
                    'zikulaezcommentsmodule_comment_getreplies',
                    {
                        module: this.$module,
                        id: this.$artId,
                        parentId: this.currentId
                    },
                    {
                        method: 'GET',
                        success: this.replyCallback.bind(this)
                    }
                );
            }
        },

        replyCallback: function (result, textStatus, jqXHR) {
            if (result) {
                //change the toggle
                var itemName = '#twiddle_' + this.currentId;
                //if the twiddle is hidden, show it.
                $(itemName).removeClass('d-none');
                $(itemName).removeClass('fa-caret-square-left');
                $(itemName).addClass('fa-caret-square-down');
                //get all the comments and add them to a form.
                var len = result.length;
                if (len !== 0) {
                    this.addSubComments(result, len);
                }
                var target = $('#itemChild_' + this.currentId);
                this.finishReplySetup(target);
            }
        },

        addSubComments: function(result, len) {
            var subComments = $('<div id="subComments_' + this.currentId + '"></div>');
            var divBlock = null;
            for (var i = 0; i < len; i++) {
                divBlock = this.$divCommentBlock.clone();
                //Remove the arrow icon. Right now, no replies to replies
                divBlock.find('span[id^=twiddle]').remove();
                var replyIcon = divBlock.find('span[id^=reply]');
                replyIcon.attr('id', 'reply_' + result[i].parentid + '_' + result[i].id );
                replyIcon.on('click', this.reply.bind(this));
                this.fillDivBlock(divBlock, result[i]);

                //add the click listener to the buttons
                this.hookUpButtons(divBlock, result[i]);
                //add the block to the commentForm.
                //Is this a memory leak? No it should not be, since remove takes it all out of the DOM including events
                subComments.prepend(divBlock);
            }
            subComments.addClass('ml-4');
            //remember this set of objects for deletion when the twiddle box is closed
            this.subComments[this.currentId] = subComments;
            //finally add the comments to the right spot in the page
            $('div[id=itemChild_' + this.currentId + ']').append(subComments);
        },

        fillDivBlock: function(inDivBlock, result) {
            //show the block
            inDivBlock.removeClass('d-none');
            //enter in the text for this comment
            var subject = inDivBlock.find('h3[id=itemSubject]');
            subject.text(result.subject);
            if (result.parentID !== 0) {
                subject.attr('id', 'itemSubject_' + result.parentId + '_' + result.id);
            } else {
                subject.attr('id', 'itemSubject_' + result.id);
            }
            var comment = inDivBlock.find('p[id=itemComment]');
            comment.html(result.comment);
            comment.attr('id', 'itemComment_' + result.id);

            var name = inDivBlock.find('i[id=itemName]');
            name.text(result.author);
            name.attr('id', 'itemName_' + result.id);
            inDivBlock.attr('id', 'itemComment_' + result.id);

            var avatar = inDivBlock.find('span[id=avatar]');
            avatar.html(result.avatar);
        },

        finishReplySetup: function(target) {
            //get the comment form and get it ready
            var comForm = this.$comForm;
            if(this.$replySubject !== '') {
                comForm.find('input[name=subject]').val(Translator.trans('Re:') + ' ' + this.$replySubject);
            }
            this.$replySubject = '';
            //this is a new comment, 0 it out.
            this.hookUpCommentButton(comForm, 0, this.currentId);

            //enter the form after the comment
            target.append(comForm);
            //indent since this is a reply
            comForm.addClass('ml-4');
            //finally show the form
            comForm.removeClass('d-none');
            //The comment form may have been ready to add a root comment
            //Just in case that is true, we show the comment button.
            //It doesn't hurt if it is already visible.
            this.$newCommentButton.removeClass('d-none');
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
