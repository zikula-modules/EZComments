(function ($) {
    $(document).ready(function () {
        $("#tableToSort").tablesorter({dateFormat: 'us'});
        tablesorter.init();
    });

    var tablesorter = {
        currentId:0,

        ajaxSettings: {
            'dataType': 'json',
            'error': this.ajaxError,
            'timeout': 10000
        },

        init: function () {
            this.cacheDom();
            this.bindEvents();
        },

        cacheDom: function () {
            this.$deleteButtons = $('span[id^=delete_]');
            this.$editButtons = $('span[id^=edit_]');
            this.$table = $("#tableToSort");
        },

        bindEvents: function () {
            this.$deleteButtons.on('click',  this.deleteComment.bind(this));
            this.$editButtons.on('click', this.editComment.bind(this));
        },

        deleteComment: function (evt){
            var itemName = evt.target.id;
            this.currentId = itemName.substring(7, itemName.length);
            //send a message to delete that item
            this.sendAjax(
                'zikulaezcommentsmodule_admin_delete',
                {'id' : this.currentId},
                {'success': this.itemDeleted.bind(this), method: 'POST'}
            );
        },

        itemDeleted: function(result, textStatus, jqXHR){
            if(result[0].success === true){
                var rowToDelete = this.$table.find("tr[id=" + this.currentId + "]");
                rowToDelete.remove();
            } else {
                window.alert(result[0].message);
            }
        },

        editComment: function(evt){
            var itemName = evt.target.id;
            this.currentId = itemName.substring(5, itemName.length);
            //send a message to delete that item
            this.sendAjax(
                'zikulaezcommentsmodule_admin_edit',
                {'id' : this.currentId},
                {'success': this.doEdit.bind(this), method: 'POST'}
            );
        },

        doEdit: function(result, textStatus, jqXHR){
            var rowToEdit = this.$table.find("tr[id=" + this.currentId + "]");
            var comment = rowToEdit.find("td[id=comment_" +  this.currentId + "]");
            comment.empty();
            comment.html("<textarea rows='10' cols='40'>" + result.comment + "</textarea>");
            var subject = rowToEdit.find("td[id=subject_" +  this.currentId + "]");
            subject.empty();
            subject.html("<input type='text' name='subject' value='" + result.subject + "' />");
            rowToEdit.find("td[id=ownerId_" +  this.currentId + "]").remove();
            rowToEdit.find("td[id=date_" +  this.currentId + "]").remove();
            rowToEdit.find("td[id=name_" +  this.currentId + "]").remove();
            rowToEdit.find("td[id=email_" +  this.currentId + "]").remove();
            rowToEdit.find("td[id=website_" +  this.currentId + "]").remove();
        },

        sendAjax: function (url, data, options) {
            //push the data object into the options
            options.data = data;
            $.extend(options, this.ajaxSettings);
            var theRoute = Routing.generate(url);
            $.ajax(theRoute, options);
        },
        ajaxError: function(jqXHR, textStatus, errorThrown){
            window.alert(textStatus + "\n" +errorThrown);
        },
    };
})(jQuery);