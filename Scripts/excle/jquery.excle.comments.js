/* =================================================
jQuery Ex-Cle Comments
----------------------------------------------------
Version: 1.0.0-beta
Propiedad: Ex-Cle s.a
WebSite: http://www.ex-cle.com.ar
================================================= */
(function(w, d, $, undefined){

  if( ! ( $ && $.fn.foundation ) ) return console.error('check if missing jQuery or jQuery.fn.foundation objects');

  $.excle = $.excle || {};
  $.excle.comments = function(){

      var
      _defaults = {
        url: {
          new: 'rest/addComment/',
          edit: 'rest/editComment/',
          delete: 'rest/deleteComment/'
        },

        dataPost: {}
      },

      _comments = function(options){

        var S = this;

        S.conf = $.extend(true, {}, _defaults, options);
        S.commentButton = $('#btnComment');
        S.errorMessage = $('#errorMessage');
        S.commentDialog = $('#commentsDialog');
        S.newMessage = $('#newMessage');
        S.cancelCommentButton = $('#cancelComment');
        S.addCommentButton = $('#addComment');
        S.formAddComment = $("#formAddComment");
        S.toolTip = $("#ToolTip");
        S.comments = $('#comments');
        S.html = $('html, body');

        //Press Enter
        S.newMessage.on('keypress.excle.comments', function(e){

          if(
            e.ctrlKey &&
            (e.which == 10) &&
            S.commentDialog.data('isOpen') &&
            S.ValidNewMessage()
          ) S.addCommentButton.trigger('click.excle.comments');

        });

        //Write Comment
        S.commentButton.on('click.excle.comments', S.openDialog.bind(S));

        //Cancel Comment
        S.cancelCommentButton.on('click.excle.comments', S.closeDialog.bind(S));

        //Add comment
        S.addCommentButton.on('click.excle.comments', function(e) {
            e.preventDefault();
            S.submit();
        });

        // Send Comment
        S.formAddComment.on('submit.excle.comments', function(e){

          e.preventDefault();

          var
            action = S.conf.url.new ? S.conf.url.new : S.formAddComment.attr('action'),
            method = S.formAddComment.attr('method');

          $.ajax({
            url: action,
            method: method,
            data: $.extend(_fn.serializeToJSON.call(S), S.conf.dataPost),
            dataType: "html",
            beforeSend: function(){
              return S.ValidNewMessage();
            },
            success: S.addNewComment.bind(S)
          });

        });

        S.refreshCommentButtonBehaivour();

        // Init
        S.loadCommentsLength();

        // Comment validation
        S.newMessage.on('keyup.excle.comments', S.ValidNewMessage.bind(S));

      };

      // Create object
      return function(options){
        _comments.prototype = $.excle.comments.fn;
        return new _comments(options);
      };

  }();

  // PROTOTYPE

  var _fn = {
    serializeToJSON: function(){

      var serialize = this.formAddComment.serializeArray(),
          data = {},
          i = 0,
          len = serialize.length,
          obj;

      for(;i<len;i+=1){
        obj = serialize[i];
         data[obj.name] = obj.value;
      }

      return data;

    }
  };

  $.excle.comments.fn = {

    refreshCommentButtonBehaivour: function () {
        var S = this;
        S.comments.find('.conflictCaseModel').each(function () {
            S.setCommentButtonBehaivour($(this));
        });
        return S;
    },

    setCommentButtonBehaivour: function ($comment) {

        if (!($comment && $comment.jquery)) return;

        var S = this;

        // Open Edit Comment Mode
        $comment.find('.edit').off('click.excle.comments').on('click.excle.comments', function (e) {
            S.edit($comment);
        });

        // Close Edit Comment Mode
        $comment.find('.cancel').off('click.excle.comments').on('click.excle.comments', function (e) {
            S.cancelEdit($comment);
        });

        // Delete Comment
        $comment.find('.delete').off('click.excle.comments').on('click.excle.comments', function (e) {
            S.delete($comment);
        });

        // Save Changes
        $comment.find('.send').off('click.excle.comments').on('click.excle.comments', function (e) {
            S.saveChanges($comment);
        });

        return this;

    },

    addNewComment: function (data, status, xhr) {

        if (!(data && status && xhr)) return;

        var $newComment = $(data);
        this.setCommentButtonBehaivour($newComment);
        this.closeDialog();
        this.comments.append($newComment.fadeIn('fast'));
        this.loadCommentsLength().html.animate({ scrollTop: $newComment.offset().top }, 'slow');
        return this;

    },

    submit: function () {
        if (this.ValidNewMessage()) {

            this.formAddComment.submit();
            this.closeDialog();
            return this;

        }
        return false;
    },

    edit: function ($comment) {
        if (!($comment && $comment.jquery)) return;
        var
        S = this,
        $observationDisplay = $comment.find('.display.observation'),
        $displayContainer = $comment.find('.display.container').hide(),
        $editionContainer = $comment.find('.edition.container').show(),
        $buttonSend = $editionContainer.find('.send'),
        $error = $comment.find('.commentError'),
        value = $observationDisplay.text().trim(),
        $textarea = $comment.find('.edition.observation').val(value),
        valid = function(){
          return S.ValidMessage($textarea, $buttonSend, $error);
        };

        $textarea
        .off('keyup.excle.comments').on('keyup.excle.comments', valid)
        .off('keypress.excle.comments').on('keypress.excle.comments', function(e){

          if(
            e.ctrlKey &&
            (e.which == 10) &&
            valid()
          ) $buttonSend.trigger('click.excle.comments');

        }).focus();

        valid();

        return this;
    },

    cancelEdit: function ($comment) {
        if (!($comment && $comment.jquery)) return;
        $comment.find('.edition.container').hide();
        $comment.find('.display.container').show();
        return this;
    },

    saveChanges: function ($comment) {
        if (!($comment && $comment.jquery)) return;

        var S = this,
        value = $comment.find('.edition.observation').val(),
        commentId = $comment.attr('data-id');

        $.ajax({
            url: S.conf.url.edit,
            data: $.extend(S.conf.dataPost, {
                observationId: commentId,
                message: value
            }),
            type: "post",
            dataType: "json",
            success: function (data) {
                $comment.attr('data-id',data.Id);
                $comment.find('.edition.container').hide();
                $comment.find('.display.container').show();
                $comment.find('h6 span').html(data.UserName + '<strong> &#183; ' + data.CreatedOn + '</strong>');
                $comment.find('.display.observation').html(data.Observation);
            }
        });
        return S;
    },

    delete: function ($comment) {
        if (!($comment && $comment.jquery)) return;

        var S = this,
        commentId = $comment.attr('data-id');

        $.ajax({
            url: S.conf.url.delete,
            data: $.extend(S.conf.dataPost, { observationId: commentId }),
            type: "post",
            dataType: "html",
            success: function (data) {

                if (data != commentId) return;

                $comment.slideUp('fast', function () {
                    $comment.remove();
                    S.loadCommentsLength();
                });
            }
        });
        return S;
    },

    closeDialog: function () {
        this.newMessage.val('');
        this.commentDialog.slideUp('fast').data('isOpen', false);
        return this;
    },

    openDialog: function () {
        this.ValidNewMessage();
        this.commentDialog.slideDown('fast').data('isOpen', true);
        this.newMessage.focus();
        return this;
    },

    getLength: function () {
        return this.comments.find('.conflictCaseModel').length;
    },

    loadCommentsLength: function () {
        var length = this.getLength();

        this.toolTip.html('');

        if (length == 1) this.toolTip.html('Hay ' + length + ' Comentario');
        else this.toolTip.html('Hay ' + length + ' Comentarios');

        return this;
    },

    validText: function(txt){

      txt = txt.toString().trim();
      var len = txt.length;

      if(txt == undefined || txt == "")
        return { code:"0", type: "empty", message: "" };

      if(len > 500)
        return { code:"0", type: "exceded", message: len + "/500 caracteres. Máximo excedido!" };

      return { code:"1", type: "valid", message: len + "/500 caracteres." };

    },

    ValidMessage: function($textarea, $sendButton, $error){

      if(!
        ($textarea && $textarea.jquery) &&
        ($sendButton && $sendButton.jquery) &&
        ($error && $error.jquery)
      ) return;

      var value = $textarea.val(),
      valid = this.validText(value);

      $error.text(valid.message);
      if(valid.code == 0){
        $sendButton.attr("disabled", "disabled");
        return false;
      }
      $sendButton.removeAttr("disabled");
      return true;
    },

    ValidNewMessage: function () {
        return this.ValidMessage(this.newMessage, this.addCommentButton, this.errorMessage);
    }
  }

}(window ? window : this, document, jQuery));
