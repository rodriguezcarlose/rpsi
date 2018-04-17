(function (w, d, $, undefined) {

    $.excle = $.excle || {};
    $.excle.fileUpload = function () {

        var
		_countAddedFiles = 0,

        _fileTypes = function () {

            var obj = { 
                
                // key: file type | value: extension type

                'application/pdf': 'pdf',
                'image/jpeg': 'jpg',
                'zip': 'zip',
                'png': 'png',
                'text\/plain': 'txt'

            };

            obj.getExtension = function(fileType) {

                for (var x in this) 
                    if (fileType.search(new RegExp('^' + x + '$', 'i')) == 0)
                        return this[x];
                return null;

            }

            obj.getType = function (fileExtension) {

                for (var x in this)
                    if (this[x] == fileExtension)
                        return x;
                return null;

            }

            return obj;

        }(),

		//_fileTypes = ["pdf", "jpe?g", "zip", "png", "text\/plain"],
		//_regexTypes = new RegExp("(" + _fileTypes.join('|') + ")$", "i"),
		_fileSize = 5000000,
        _middleWareResolve = function (resolve) { resolve() },

		_defaults = {
		    url: {
		        upload: '',
		        delete: '',
		        new: '',
		        download: ''
		    },
		    fileupload: {
		        dataType: 'html',
		        type: 'post',
		        autoUpload: false,
		        acceptFileTypes: /(\.|\/)(pdf)$/i,
		        maxFileSize: _fileSize,
		        disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
		        previewMaxWidth: 100,
		        previewMaxHeight: 100,
		        previewCrop: true,
		        limitConcurrentUploads: 3
		    },
		    dataPost: {},
		    onDeleteFile: _middleWareResolve,
		    onDownloadFile: _middleWareResolve,
		    onAddFile: _middleWareResolve,
		    onUploadFile: _middleWareResolve,
		    onValidError: function () { }
		},

		_fileUpload = function (options) {

		    var
		        clickedFileUploadButton,
		        returnValue,
		        _config = $.extend(true, {}, _defaults, options),
		        _$attachmenPanel = new $.fn.init('[data-fileupload]', d).first(), //new $.fn.init(d.getElementById('Files'), d),
		        _$fileUploadButton = _$attachmenPanel.find('[data-fu="input"]').first(),
		        _$fileUploadInput = _$attachmenPanel.find('input[type="file"]').first(), //_$attachmenPanel.find(d.getElementById('fileupload')),
		        _$sendButton = _$attachmenPanel.find('[data-fu="sendAll"]').first(), //_$attachmenPanel.find(d.getElementById('SendButton')),
		        _$abortButton = _$attachmenPanel.find('[data-fu="abortAll"]').first(), //_$attachmenPanel.find(d.getElementById('AbortButton')),
		        _$delteButton = _$attachmenPanel.find('[data-fu="deleteAll"]').first(), //_$attachmenPanel.find(d.getElementById('DeleteButton')),
		        _$filesContainer = _$attachmenPanel.find('[data-fu="files"]').first(), //new $.fn.init(d.getElementById('files'), d),
		        _$UploadedsContainer = _$attachmenPanel.find('[data-fu="uploaded"]').first(), //new $.fn.init(d.getElementById('uploadedFiles'), d),
		        //_$FileTabs = _$attachmenPanel.find('[data-fu="files-tab"]'), //new $.fn.init(d.getElementById('FileTabs'), d),
		        _$ConfirmFileTab = _$attachmenPanel.find('[data-fu="files-label"]').first(), //_$FileTabs.find('a[href="#files"]'),
		        _$UploadedFileTab = _$attachmenPanel.find('[data-fu="uploaded-label"]').first(), //_$FileTabs.find('a[href="#uploadedFiles"]'),
		        _$meter = _$filesContainer.find('[data-fu="progressbar"]').first(), //$_$filesContainer.find('.progress .meter'),
		        _submitCallbacks = $.Callbacks(),
		        _abortCallbacks = $.Callbacks(),


		        _executeMiddleWare = function(callback, def) {

		            if ($.isFunction(callback)) {

		                var adef = [def.resolve.bind(def), def.reject.bind(def)],
		                    args = Array.prototype.slice.call(arguments, 2);

		                callback.apply(_config, $.merge(adef, args));

		            } else {

		                _middleWareResolve(def);

		            }

		            return def;

		        },

		        _countFiles = function() {

		            var count = _$filesContainer.find('.fileToUpload').length;

		            if (count <= 0) {
		                _$ConfirmFileTab.text('Archivos a confirmar');
		                _$filesContainer.addClass('empty');
		            } else if (count === 1) {
		                _$ConfirmFileTab.text(count + ' Archivo a confirmar');
		                _$filesContainer.removeClass('empty');
		            } else if (count > 1) {
		                _$ConfirmFileTab.text(count + ' Archivos a confirmar');
		                _$filesContainer.removeClass('empty');
		            }

		        },

		        _countUploadedFiles = function() {

		            var count = _$UploadedsContainer.find('.uploadedFile').length;

		            if (count <= 0) {
		                _$UploadedFileTab.text('Archivos cargados');
		                _$UploadedsContainer.addClass('empty');
		            } else if (count === 1) {
		                _$UploadedFileTab.text(count + ' Archivo cargado');
		                _$UploadedsContainer.removeClass('empty');
		            } else if (count > 1) {
		                _$UploadedFileTab.text(count + ' Archivos cargados');
		                _$UploadedsContainer.removeClass('empty');
		            }
		        },

		        _getNewDocument = function(name, index, type, extension) {

		            return $.ajax({
		                url: _config.url.new,
		                type: "post",
		                dataType: "html",
		                data: {
		                    name: name,
		                    index: index,
		                    type: type,
		                    extension: extension
		                }
		            });

		        },

		        _getTypeDocument = function (name) {

		            var ext = name.substring(name.lastIndexOf(".") + 1);
		            return _fileTypes.getType(ext) || 'unrecognised';

		        };

		    // Emule click file input
		    //_$fileUploadButton.on('click', function() {
		    //    _$fileUploadInput.trigger('click').focus();
		    //});
		    //_$fileUploadInput.on('click', function(e) { e.stopPropagation() });

		    _countFiles();
		    _countUploadedFiles();

		    _$sendButton.click(function () {

		        _submitCallbacks.fire();

		    });

		    _$abortButton.on('click.PIAC2.fileUpload', function () {

		        _abortCallbacks.fire();

		    });

		    _$delteButton.on('click.PIAC2.fileUpload', function () {

		        _$abortButton.trigger('click.PIAC2.fileUpload');
		        $('#files .fileToUpload').remove();
		        _countFiles();

		    });

		    //File Upload Config
		    returnValue = _$fileUploadInput.fileupload($.extend({}, _config.fileupload, {

		        url: _config.url.upload,
		        fileInput: _$fileUploadInput,
		        dropZone: _$filesContainer,
		        pasteZone: _$filesContainer

		    }))
			.on('fileuploadadd', function (e, __data) {

			    var isValid = true;

			    $.each(__data.files, function (index, __file) {

			        /***************************************
								VALIDACIONES
					***************************************/

			        __file.type = __file.type || _getTypeDocument(__file.name);

			        //if (!_regexTypes.test(__file.type)) {
			        if (!_fileTypes.getExtension(__file.type)) {
			            _config.onValidError.call(_config, "Atención", "El tipo del archivo " + __file.name + " no esta soportado", 2, __file); //modalMessage("Atención", "El tipo del archivo " + __file.name + " no esta soportado", 2);
			            return isValid = false;
			        }

			        if (__file.size && __file.size > _fileSize) {
			            _config.onValidError.call(_config, "Atención", "El tamaño del archivo " + __file.name + " debe ser menor a " + (_fileSize / 1000000) + "Mb.", 2, __file); //modalMessage("Atención", "El tamaño del archivo " + __file.name + " debe ser menor a " + (_fileSize / 1000000) + "Mb.", 2);
			            return isValid = false;
			        }


			        /***************************************
								Extraigo extension
					***************************************/

			        var indexExt = __file.name.lastIndexOf("."), deferred = $.Deferred();

			        __file.originalNameWithoutExt = __file.name.substring(0, indexExt);
					__file.extName = __file.name.substring(indexExt);

			        /**************************************/

					__file.index = ++_countAddedFiles;

			        /* Ejecuto Deferred para Agregar Archivo */
					_executeMiddleWare(_config.onAddFile, deferred, __file).done(function () {

					    var newDocumentJqXHR = _getNewDocument(__file.originalNameWithoutExt, __file.index, __file.type, __file.extName),
                            submitCallback;

					    newDocumentJqXHR.done(function (html, textStatus, jqXHR) {

					        __file.$container = $(html);
					        __file.$uploadButton = __file.$container.find('[data-fu="upload"]').first(); //__file.$container.find('button[name^="uploadButton"]');
					        __file.$cancelButton = __file.$container.find('[data-fu="cancel"]').first(); //__file.$container.find('button[name^="cancelButton"]');
					        __file.$nameInput = __file.$container.find('[data-fu="name"]').first(); //__file.$container.find('input[type="text"][name$="Name"]');
					        __file.$noteInput = __file.$container.find('[data-fu="note"]').first(); //__file.$container.find('input[type="text"][name$="Note"]');
					        __file.$meter = __file.$container.find('[data-fu="progressbar"]').first(); //__file.$container.find('span.meter');
					        __file.$errorContainer = __file.$container.find('[data-fu="errors"]').first(); //__file.$container.find('tr.errorMessage td');

					        __file.$container.data('fileUpload', __file);

					        /*******************************************************
                            La funcion "submitCallback" es llamada tanto por el
                            boton "cargar" del archivo como el boton
                            "Cargar todos los archivos" para el envío
                            simultaneo.
                            *******************************************************/

					        submitCallback = function () {

					            var
                                    deferred = $.Deferred(),
                                    inputNameValue = __file.$nameInput.val(),
                                    inputNotevalue = __file.$noteInput.val();

					            inputNameValue = (inputNameValue && inputNameValue.trim()) || "";
					            inputNotevalue = (inputNotevalue && inputNotevalue.trim()) || "";

					            /********************************************************
                                VALIDACIONES
                                ********************************************************/

					            __file.$errorContainer.html('');
					            __file.$nameInput.removeClass('input-validation-error');
					            __file.$noteInput.removeClass('input-validation-error');

					            // Valido si el nombre del archivo no esta vacio

					            if (inputNameValue === "") {

					                __file.$errorContainer.prepend($message = $('<p/>').attr('data-alert', '').addClass('alert-box alert').text("El archivo debe contener nombre"));
					                __file.$nameInput.addClass('input-validation-error');
					                return false;

					            }

					            // Valido si el nombre tiene menos de 100 caracteres

					            if (inputNameValue.length > 100) {

					                __file.$errorContainer.prepend($message = $('<p/>').attr('data-alert', '').addClass('alert-box alert').text("El campo nombre debe tener como máximo, 100 caracteres"));
					                __file.$nameInput.addClass('input-validation-error');
					                return false;

					            }

					            // Valido si la nota tiene menos de 250 caracteres

					            if (inputNotevalue.length > 250) {

					                __file.$errorContainer.prepend($message = $('<p/>').attr('data-alert', '').addClass('alert-box alert').text("El campo nota debe tener como máximo, 250 caracteres"));
					                __file.$noteInput.addClass('input-validation-error');
					                return false;

					            }

					            __file.inputNameValue = inputNameValue;
					            __file.inputNoteValue = inputNotevalue;

					            // Evito que la funcion siga llamandose luego del submit por el boton "cargar todos los archivos"
					            _submitCallbacks.remove(submitCallback);

					            /* Ejecuto Deferred para Cargar Archivo */
					            _executeMiddleWare(_config.onUploadFile, deferred, __file).done(function () {

					                __file.submitJqXHR = __data.submit();

					            });

					        };

					        __file.$uploadButton.on('click.PIAC2.fileUpload', submitCallback);

					        _submitCallbacks.add(submitCallback);

					        __file.$cancelButton.on('click.PIAC2.fileUpload', function () {

					            __file.$container.fadeOut('fast', function () {
					                __file.$container.remove();
					                _countFiles();
					            });
					            _submitCallbacks.remove(submitCallback);

					        });

					        __file.$container.hide().appendTo(_$filesContainer).fadeIn('slow');

					        _countFiles();

					    });

					    newDocumentJqXHR.fail(function (data, textStatus, jqXHR) {

					        //return modalMessage("Error", "Hubo un problema interno del servidor al agregar el archivo" + __file.name, -1);
					        return alert("Hubo un problema interno del servidor al agregar el archivo" + __file.name);

					    });

					});

			    });

			    return isValid;

			})
			.on('fileuploadsubmit', function (e, __data) {

			    $.each(__data.files, function (index, __file) {

			        /*******************************************************
					La funcion "abortCallback" es llamada tanto por el
					boton "cargar" del archivo como el boton
					"Cancelar todas las subidas" para abortar la
					subida simultanea.
					*******************************************************/

			        var abortCallback = function () {

			            _abortCallbacks.remove(abortCallback);

			            __file.$uploadButton.remove();
			            __file.submitJqXHR.abort();

			        };

			        __file.$uploadButton.off('click.PIAC2.fileUpload')
						.text('abort').on('click.PIAC2.fileUpload', abortCallback);

			        _abortCallbacks.add(abortCallback);

			        /*******************************************************/

			        __data.formData = $.extend({}, _config.dataPost, {

			            name: __file.inputNameValue + __file.extName,
			            note: __file.inputNoteValue,
			            type: __file.type

			        });

			    });

			    return __data.isValid;

			})
			.on('fileuploaddone', function (e, __data) {

			    var $html = $(__data.result);

			    $.each(__data.files, function (index, __file) {

			        __file.$container.fadeOut('slow', function () {
			            __file.$container.remove();
			            _countFiles();
			        });

			        $html.hide().appendTo(_$UploadedsContainer).fadeIn('slow', function () { _countUploadedFiles(); });

			        $html.data('fileUpload', __file);

			    });

			})
			.on('fileuploadfail', function (e, __data) {

			    var $message = $('<p/>').attr('data-alert', '').addClass('alert-box alert').text(__data.errorThrown);

			    $.each(__data.files, function (index, __file) {

			        __file.$errorContainer.prepend($message);

			    });

			})
			.on('fileuploadalways', function (e, __data) {

			    _$meter.css('width', '0%').text('');

			})
			.on('fileuploadprogress', function (e, __data) {

			    var percent = parseInt(__data.loaded / __data.total * 100, 10) + "%";

			    $.each(__data.files, function (index, __file) {

			        __file.$meter.animate({

			            width: percent

			        }, 'fast');

			    });

			})
			.on('fileuploadprogressall', function (e, __data) { // Barra de progreso de todo

			    var percent = parseInt(__data.loaded / __data.total * 100, 10) + "%";

			    _$meter.css('width', percent).text(percent);

			});

		    $('[data-fu="uploaded"]:eq(0)') //$(d.getElementById('uploadedFiles'))
			.off('click.PIAC2.fileUpload', '[data-fu="uploadedFile"] [data-fu="download"], [data-fu="uploadedFile"] [data-fu="delete"]')
			.on('click.PIAC2.fileUpload', '[data-fu="uploadedFile"] [data-fu="download"]', function (event) {

			    var
                    deferred = $.Deferred(),
					$self = $(event.target),
                    $table = $self.parents('[data-fu="uploadedFile"]'),
					input = $('<input type="hidden" name="id" value="' + $table.data('fileid') + '" />');

			        /* Ejecuto Deferred para Agregar Archivo */
			        _executeMiddleWare(_config.onDownloadFile, deferred, $table).done(function() {

		                //send request
		                $('<form action="' + _config.url.download + '" method="get">')
                            .append(input)
                            .appendTo('body').submit().remove();

		            });

			})
			.on('click.PIAC2.fileUpload', '[data-fu="uploadedFile"] [data-fu="delete"]', function (event) {

			    var
                    deferred = $.Deferred(),
					$self = $(event.target),
					$table = $self.parents('[data-fu="uploadedFile"]');
                    
			        /* Ejecuto Deferred para Agregar Archivo */
			        _executeMiddleWare(_config.onDeleteFile, deferred, $table).done(function () {
                        
		                $.ajax({
		                    url: _config.url.delete,
		                    type: "post",
		                    dataType: "json",
		                    data: {
		                        id: $table.data('fileid')
		                    },
		                    success: function (res) {
		                        $table.remove();
		                        _countUploadedFiles();
		                    }
		                });

		            });

		        });

		    return returnValue;

		}

        return _fileUpload;

    }();

}(window ? this : window, document, jQuery))
