(function (w, d, $, undefined) { // Scope

    var _editor = function () {

        var
            modal = d.createElement('div'),
            close = d.createElement('a'),
            container = d.createElement('div');

        modal.className = 'reveal-modal editor ';
        modal.dataset.reveal = '';
        modal.dataset.editor = '';
        close.className = 'close-reveal-modal';
        close.appendChild(d.createTextNode('\u00d7'));
        container.className = 'content';

        modal.appendChild(close);
        modal.appendChild(container);

        return modal;

    }();

    $.excle = $.excle || {};
    $.excle.modalEditor = function(){


        var
        _defaults = { // Statics
            class: 'small', // Clase del reveal por defecto
            content: "", // Contenido a mostrar en el Modal Editor
            actions: function(){} // Callback cuando se despliega el Modal Editor
        },

        _modalEditor = function(options){ // Constructor

            var self = this;

            $.extend(true, self, {}, _defaults, options);

            self.modal = $(_editor.cloneNode(true)).addClass(self.class);
            self.container = self.modal.find('.content').first().html(self.content);

            self.callbacks = $.Callbacks('unique memory');

            if ($.isArray(self.actions)) self.Actions.apply(self, self.actions);

            else self.Actions(self.actions);

        };

        return function(o){ // Factory
            _modalEditor.prototype = $.excle.modalEditor.fn;
            return new _modalEditor(o);
        }

    }();

    $.excle.modalEditor.fn = { // Métodos
        Open: function ( keep ) {

            keep = keep === undefined ? true : keep;

            var self = this;

            if (!!keep === false) self.Content(self.content);

            self.modal.foundation('reveal', 'open');

            self.callbacks.fire.call(self, self.container, self.modal);

            return self;
        },

        Close: function () {

            if (this.modal.jquery) this.modal.off('close.modalEditor').foundation('reveal', 'close');

        },

        Content: function (html) {

            this.container.html(this.content = html);
            return this;

        },

        Actions: function (memory) {

            this.callbacks.add.apply(this, arguments);
            return this;

        },

        RemoveAction: function() {

            this.callbacks.remove.apply(this, arguments);
            return this;

        }
    }

    /*Modal Editor******************************************************/
    /*******************************************************************/
    w.modalEditor = function(url) {

        $.get(url, function (html) {

            modalEditorContent(html);

        });

    }

    w.modalEditorContent = function(html) {

        $('#ModalEditor .content').html(html);
        $('#ModalEditor')
            .foundation('reveal', 'reflow')
            .foundation('reveal', 'open');

    };

}(window?window:this, document, jQuery)); // End Scope
