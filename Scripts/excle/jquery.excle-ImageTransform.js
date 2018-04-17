(function ($) {

    $.EcImageTransformation = function (element, options) {

        // plugin's default options
        var defaults = {
            x: 0,
            y: 0,
            width: 0, //TODO
            height: 0, //TODO
            brightness: 100,
            contrast: 100,
            opacity: 1,
            saturation: 100,
            hue: 0,
            invert: 0,
            rotation: 0, //in radians
            zoom: 1,
            zindex: 1,
            alignRight: false,
            flipH: null,
            flipV: null,
            viewPortElement: null,
            fingerPrint: null,
            rotationHandleActive: true, //TODO
            zoomHandleActive: true, //TODO
            titleActive: true,
            image: null,
            errorImage: null,
            title: null,
            //Lightbox
            isLightBox: false,
            //POIS
            poiId: 0,
            pois: [],
            poisActive: false,
            // Events
            onBrightnessChange: function () { }, //TODO
            onContrastChange: function () { }, //TODO
            onOpacityChange: function () { }, //TODO
            onInvertChange: function () { }, //TODO
            onImageLoadComplete: function () { },
            onLoadComplete: function () { },
            onImageLoadError: function () { },
            onLightboxOpen: function () { }, //TODO
            onLightboxClose: function () { }, //TODO
            getFpId: function () { }
        }

        // to avoid confusions, use "plugin" to reference the 
        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        // plugin's properties will be available through this object like:
        // plugin.settings.propertyName from inside the plugin or
        // element.data('EcImageTransformation').settings.propertyName from outside the plugin, 
        // where "element" is the element the plugin is attached to;
        plugin.settings = {}

        // default

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.init = function () {
            // user-provided options (if any)
            plugin.settings = $.extend({}, defaults, options);
            plugin.userDefaultOptions = $.extend({}, defaults, options); // Save User Default Options
            crateElements();
            setTransformations();
        }


        //////////////////////////////////////////////////////
        // private props /////////////////////////////////////
        var isInitialization = true;

        //////////////////////////////////////////////////////
        // image elements ////////////////////////////////////
        var poiWrapper = $('<div>');
        var title = $('<h4>');
        var imgContainer = $('<div>');
        var img = $('<img>');

        //////////////////////////////////////////////////////
        // lightbox elements /////////////////////////////////
        var isLightBoxOpen = false;
        var lightBoxBg = $('<div class="LightboxBg">');
        var lightBoxClose = $('<div class="LightboxButton LightboxClose"/>');
        //var lightboxMenu = $('<div class="LightboxButton LightboxMenu"/>');
        var lightboxDownload = $('<div class="LightboxButton LightboxDownload"/>');
        var lightboxReset = $('<div class="LightboxButton LightboxReset"/>');

        var imageLoadComplete = {
            status: false,
            get: function () {
                return this.status;
            },
            set: function (val) {
                this.status = val;
                if (val == true)
                    plugin.settings.onImageLoadComplete();
            }
        };

        var loadComplete = {
            status: false,
            get: function () {
                return this.status;
            },
            set: function (val) {
                this.status = val;
                if (val == true)
                //plugin.settings.onLoadComplete();
                    setInitialImagePosition();
            }
        }

        //////////////////////////////////////////////////////
        // private methods ///////////////////////////////////
        //////////////////////////////////////////////////////

        //Creates the containers    
        var crateElements = function () {

            //Set elements and DOM transformation
            imgContainer.addClass('EcImageTransformation');
            imgContainer.addClass('ImageTransform');
            imgContainer.css('z-index', plugin.settings.zindex);
            imgContainer = $element.wrapAll(imgContainer).parent();
            
            if (plugin.settings.titleActive) { // if title propertie is set
                title.html(plugin.settings.title);
                $element.before(title);
            }

            //Set View Port
            if (typeof plugin.settings.viewPortElement != 'undefined' && plugin.settings.viewPortElement) { // if viewport area is defined

                plugin.settings.viewPortElement.addClass("viewPort"); // adds the class to the container

                if (plugin.settings.isLightBox) { // if lightbox mode is set

                    plugin.settings.viewPortElement.addClass("Lightbox"); // adds classes to container
                    plugin.settings.viewPortElement.addClass("hide"); // adds classes to container
                    plugin.settings.viewPortElement.prepend(lightBoxClose); // adds close button
                    plugin.settings.viewPortElement.prepend(lightboxDownload); // adds download button
                    plugin.settings.viewPortElement.prepend(lightboxReset); // adds reset button
                    plugin.settings.viewPortElement.prepend(lightBoxBg); // adds lightbox background

                    setLightboxEvents(); // initializes lightbox action events 
                }
            } else { // if its not set, then change the image container CSS position propertie to FIXED
                imgContainer.css('position', 'fixed');
            }

            if (plugin.settings.poisActive) { // if POIs is set
                poiWrapper.addClass('poiWrapper');
                poiWrapper = $element.wrap(poiWrapper).parent();
            }

            if (typeof plugin.settings.image != 'undefined' && plugin.settings.image) { // if image is set from properties instead of from the element
                plugin.setImage(plugin.settings.image);
            } else { //if its not set, then centers the image
                setInitialImagePosition();
            }
        }

        var setInitialImagePosition = function () {
            var x, y;
            if (plugin.userDefaultOptions.x) { // if x position is predefined

                if (plugin.settings.alignRight) { // if image is s et to align to right
                    x = plugin.settings.viewPortElement.width() - imgContainer.width() - plugin.userDefaultOptions.x;
                } else {
                    x = plugin.userDefaultOptions.x;
                }

            } else {

                if (typeof plugin.settings.viewPortElement != 'undefined' && plugin.settings.viewPortElement) { // if image has viewport defined
                    x = imgContainer.parent().width() / 2 - imgContainer.width() / 2;
                } else {
                    x = $(document).width() / 2 - imgContainer.width() / 2;
                }
                
            }

            if (plugin.userDefaultOptions.y) { // if y position is predefined
                y = plugin.userDefaultOptions.y;
            } else {
                
                if (typeof plugin.settings.viewPortElement != 'undefined' && plugin.settings.viewPortElement) {// if image has viewport defined
                    y = imgContainer.parent().height() / 2 - imgContainer.height() / 2;
                } else {
                    y = $(document).height() / 2 - imgContainer.height() / 2;
                }
                
            }

            if (plugin.userDefaultOptions.rotation) {
                plugin.setRotation(plugin.userDefaultOptions.rotation);
            }

            if (plugin.userDefaultOptions.zoom) {
                plugin.setZoom(plugin.userDefaultOptions.zoom);
            }

            plugin.setPosition(x, y);
        }

        // Initializes transformations
        var setTransformations = function () {

            imgContainer.draggable({ // initializes jQuery UI DRAGGABLE
                stop: function () {
                    plugin.setPosition(parseInt(imgContainer.css('left')), parseInt(imgContainer.css('top')));
                },
                start: function (e, ui) { //Select Image on drag
                    if ($(e.target).find('#' + $element.attr('id')).length) { // Sl
                    //if (e.target.id == $element.attr('id')) { // Sl
                        var target = e.target.id;
                        if (target != $element.attr('id')) {
                            imgContainer.addClass('selected');
                        }
                    }
                }
            }).transformable({ // initializes js Transformable
                scale: constrainscale,
                rotateStop: plugin.setRotation,
                scaleStop: plugin.setZoom,
                scalable: plugin.settings.zoomHandleActive,
                skewable: false,
                rotatable: plugin.settings.rotationHandleActive
            });

            //Select Image
            imgContainer.click(function () { // sets Click Event for selection
                plugin.setSelection();
            });

            //Unselect Image
            $(document).mousedown(function (e) { // unsets selection

                if (e.target.id != $element.attr('id')) {
                    var target = e.target.id;
                    if (target != $element.attr('id')) {
                        imgContainer.removeClass('selected');
                    }
                }

            });

            //Keyboard position
            $(document).keydown(function (e) {
                if (imgContainer.hasClass('selected')) {
                    switch (e.which) {
                    case 38: // up
                        e.preventDefault();
                        plugin.setPosition(plugin.settings.x, plugin.settings.y - 5);
                        break;

                    case 40: // down
                        e.preventDefault();
                        plugin.setPosition(plugin.settings.x, plugin.settings.y + 5);
                        break;

                    case 37: // left
                        e.preventDefault();
                        plugin.setPosition(plugin.settings.x - 5, plugin.settings.y);
                        break;

                    case 39: // right
                        e.preventDefault();
                        plugin.setPosition(plugin.settings.x + 5, plugin.settings.y);
                        break;

                    default:
                        break;
                    }
                }
            });

            //Mousewheel Zoom
            var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel";
            imgContainer.bind(mousewheelevt, function (e) {
                e.preventDefault();
                //var img = $(this);
                //var tx = img.getTransform();
                var delta = extractDelta(e);

                if (delta <= -120) {
                    //UP
                    if (e.shiftKey) {
                        if (e.ctrlKey) {
                            if (plugin.getInvert() > 0) {
                                plugin.setInvert(plugin.getInvert() - 10); // invert +
                            }
                        }
                        else {
                            plugin.setRotation(plugin.getRotation() - 2); // rotate +
                        }
                    }
                    else if (e.altKey) {
                        if (plugin.getZoom() > 0.2) {
                            plugin.setZoom(plugin.getZoom() - 0.1); // scale +
                        }
                    }
                    else if (e.ctrlKey) {
                        if (plugin.getOpacity() > 0)
                            plugin.setOpacity(plugin.getOpacity() - 0.1); // opacity +
                    }
                }
                else {
                    //DOWN
                    if (e.shiftKey) {
                        if (e.ctrlKey) {
                            if (plugin.getInvert() < 100) {
                                plugin.setInvert(plugin.getInvert() + 10); // invert -
                            }
                        }
                        else {
                            plugin.setRotation(plugin.getRotation() + 2); //rotate -
                        }
                    }
                    else if (e.altKey) {
                        plugin.setZoom(plugin.getZoom() + 0.1); // scale -
                    }
                    else if (e.ctrlKey) {
                        if (plugin.getOpacity() < 1)
                            plugin.setOpacity(plugin.getOpacity() + 0.1); // opacity -
                    }
                }
            });

        }

        // Initializes Lightbox Events
        var setLightboxEvents = function () {

            lightBoxClose.click(function () {
                plugin.toggleLightbox();
            });

            lightboxReset.click(function () {
                plugin.resetTransform();
            });

            lightBoxBg.click(function () {
                plugin.toggleLightbox();
            });

            lightboxDownload.click(function (e) {
                e.preventDefault();
                var link = document.createElement('a');
                link.href = $element.attr('src');
                link.download = 'image.jpeg';
                document.body.appendChild(link);
                link.click();
            });
        }

        // Helper: Delta Extraction
        function extractDelta(e) {
            if (e.wheelDelta) { return e.wheelDelta; }
            if (e.originalEvent.detail) { return e.originalEvent.detail * -40; }
            if (e.originalEvent && e.originalEvent.wheelDelta) { return e.originalEvent.wheelDelta; }
        };

        // Helper: Converts from degrees to radians.
        var degreesToRadians = function (degrees) {
            return degrees * Math.PI / 180;
        };

        // Helper: Converts from radians to degrees.
        var radiansToDegrees = function (radians) {
            return radians * 180 / Math.PI;
        };

        /////////////////////////////////////////////////////
        // Public methods ///////////////////////////////////
        /////////////////////////////////////////////////////

        /////////////
        //LightBox
        /////////////
        plugin.toggleLightbox = function () {
            if (isLightBoxOpen) {
                plugin.settings.viewPortElement.fadeOut();
                isLightBoxOpen = false;
            } else {
                plugin.settings.viewPortElement.fadeIn();
                isLightBoxOpen = true;
            }
        }


        /////////////
        //Setters
        /////////////

        //Set selection
        plugin.setSelection = function () {
            imgContainer.addClass('selected');
        }

        //Set Image
        plugin.setImage = function (val) {
            imageLoadComplete.status = false;
            if (val != null) {
                plugin.settings.image = val; //sets the image in the plugin prop

                $element.attr('src', ''); //clears the image
                imgContainer.addClass('loading'); //adds loading class

                $element
                    .on('load', function () { //load with no errors
                        imgContainer.removeClass('loading'); //removes loading class
                        imageLoadComplete.set(true); //sets load complete 
                        if (isInitialization) { //asks if its initialization
                            loadComplete.set(true);
                            isInitialization = false;
                        }
                        if (plugin.settings.isLightBox)
                            plugin.resetTransform();
                    })
                    .on('error', function () { //load with errors
                        imgContainer.removeClass('loading'); //removes loading class
                        $element.attr('src', defaults.errorImage); //dets error image
                        plugin.settings.onImageLoadError(); //triggers image error event
                        imageLoadComplete.set(true); //sets load complete 
                        if (isInitialization) { //asks if its plugin initialization
                            loadComplete.set(true);
                            isInitialization = false;
                        }
                        if (plugin.settingsisLightBox)
                            plugin.resetTransform();
                    })
                    .attr('src', val);
            }

            //Set Pois
            if (plugin.settings.poisActive) {
                $element.off('contextmenu');
                $element.bind('contextmenu', function (e) {
                    e.preventDefault();
                    var res = offsetXY(e, $element[0]);
                    plugin.createPoint($element, res.x, res.y);
                    return false;
                });

                //Borro de la vista los puntos de interes y agreglo lso q corresponden a la huella seleccionada
                plugin.clearAllPois();

                $.each(plugin.settings.pois, function (i, e) {
                    if (e.relatedFpId === plugin.settings.getFpId()) {
                        plugin.addPoi(e);
                    }
                });
            }
        }

        //Set Position
        plugin.setPosition = function (x, y) {

            if (x != null) {
                imgContainer.css({ 'left': x });
                plugin.settings.x = x;
            }

            if (y != null) {

                imgContainer.css({ 'top': y });
                plugin.settings.y = y;
            }

        }

        //Set Zoom
        plugin.setZoom = function (val) {
            if (isNaN(parseInt(val))) { //its NaN if it comes from TransformableJS scaleStop Event
                imgContainer.setTransform('scalex', imgContainer.getTransform().scalex);
                imgContainer.setTransform('scaley', imgContainer.getTransform().scaley);
            } else {
                imgContainer.setTransform('scalex', val);
                imgContainer.setTransform('scaley', val);
            }
            plugin.settings.zoom = imgContainer.getTransform().scalex;
        }

        //Set Rotation
        plugin.setRotation = function (val) {
            //Degrees To Radians
            var rad = degreesToRadians(val);

            if (isNaN(parseInt(rad))) { //its NaN if it comes from TransformableJS rotateStop Event
                imgContainer.setTransform('rotate', imgContainer.getTransform().rotate);
            } else {
                imgContainer.setTransform('rotate', rad);
            }

            plugin.settings.rotation = imgContainer.getTransform().rotate;
        }

        //Set Opacity
        plugin.setOpacity = function (val) {
            $element.css('opacity', val);
            plugin.settings.opacity = val;
        }

        //Set Brightness
        plugin.setBrightness = function (val) {
            $element.css('filter',
                'brightness(' + val + '%) ' +
                'contrast(' + plugin.settings.contrast + '%) ' +
                'saturate(' + plugin.settings.saturation + '%) ' +
                'hue-rotate(' + plugin.settings.hue + 'deg) ' +
                'invert(' + plugin.settings.invert + '%)');
            plugin.settings.brightness = val;
        }

        //Set Contrast
        plugin.setContrast = function (val) {
            $element.css('filter',
                'brightness(' + plugin.settings.brightness + '%) ' +
                'contrast(' + val + '%) ' +
                'saturate(' + plugin.settings.saturation + '%) ' +
                'hue-rotate(' + plugin.settings.hue + 'deg) ' +
                'invert(' + plugin.settings.invert + '%)');
            plugin.settings.contrast = val;
        }

        //Set Invert
        plugin.setInvert = function (val) {
            $element.css('filter',
                'brightness(' + plugin.settings.brightness + '%) ' +
                'contrast(' + plugin.settings.contrast + '%) ' +
                'saturate(' + plugin.settings.saturation + '%) ' +
                'hue-rotate(' + plugin.settings.hue + 'deg) ' +
                'invert(' + val + '%)');
            plugin.settings.invert = val;
        }

        //Set Saturation
        plugin.setSaturation = function (val) {
            $element.css('filter',
                'brightness(' + plugin.settings.brightness + '%) ' +
                'contrast(' + plugin.settings.contrast + '%) ' +
                'saturate(' + val + '%) ' +
                'hue-rotate(' + plugin.settings.hue + 'deg) ' +
                'invert(' + plugin.settings.invert + '%)');
            plugin.settings.saturation = val;
        }

        //Set Hue
        plugin.setHue = function (val) {
            $element.css('filter',
                'brightness(' + plugin.settings.brightness + '%) ' +
                'contrast(' + plugin.settings.contrast + '%) ' +
                'saturate(' + plugin.settings.saturation + '%) ' +
                'hue-rotate(' + val + 'deg) ' +
                'invert(' + plugin.settings.invert + '%)');
            plugin.settings.hue = val;
        }

        //Set Z-Index
        plugin.setZindex = function (val) {
            imgContainer.css('z-index', val);
            plugin.settings.zindex = val;
        }

        //Reset Transformations
        plugin.resetTransform = function () { // resets all transformation to user default options

            setInitialImagePosition();
            plugin.setRotation(plugin.userDefaultOptions.rotation);
            plugin.setZoom(plugin.userDefaultOptions.zoom);
            plugin.setHue(plugin.userDefaultOptions.hue);
            plugin.setSaturation(plugin.userDefaultOptions.saturation);
            plugin.setInvert(plugin.userDefaultOptions.invert);
            plugin.setContrast(plugin.userDefaultOptions.contrast);
            plugin.setBrightness(plugin.userDefaultOptions.brightness);
            plugin.setOpacity(plugin.userDefaultOptions.opacity);
        }

        //POI (point of interest)
        plugin.addPoi = function (obj) {

            var poiColor = obj.colorId > 10 ? parseInt(obj.colorId.toString().slice(-1)) : obj.colorId;
            var $pointOfInterest = $("<div class='POI'><div class='objPOI poiColor" + poiColor + "'/></div>");
            $pointOfInterest.data('x', obj.x);
            $pointOfInterest.data('y', obj.y);
            $pointOfInterest.data('id', obj.id);
            $pointOfInterest.data('colorId', poiColor);
            $pointOfInterest.data('relatedFpId', obj.relatedFpId);
            $pointOfInterest.data('relatedFpOrigin', obj.origin);
            $pointOfInterest.attr('id', 'poi_' + obj.id);
            $pointOfInterest.css({ top: obj.y, left: obj.x, position: 'absolute' });

            //POIs dragables y transformables
            $pointOfInterest.draggable({
                containment: 'parent',
                drag: function () {
                    POItransformControl($(this));
                }
            }).transformable({
                scalable: false,
                skewable: false,
                rotatable: false,
                containment: true
            });
            $pointOfInterest.bind('contextmenu', function (e) {
                e.preventDefault();
                plugin.deletePoi($(this));
                return false;
            });
            poiWrapper.append($pointOfInterest);
        }

        //POI: Create POI
        plugin.createPoint = function (obj, absRelX, absRelY) {

            var relatedFpId = plugin.settings.getFpId(); //Id de la huella en el contenedor

            //Obtengo el ultimo valor de colorId respecto al FP relacionado
            var colorArray = [];
            $.each(plugin.settings.pois, function (i, e) {
                if (e.relatedFpId === plugin.settings.getFpId()) {
                    colorArray.push(e.colorId);
                }
            });
            var poiClass = getNextColor(colorArray);

            //POI Id
            plugin.settings.poiId++;

            //POI data
            var poiData = {
                'x': absRelX,
                'y': absRelY,
                'id': plugin.settings.poiId,
                'relatedFpId': relatedFpId,
                'colorId': null
            };

            poiData.colorId = poiClass;//valor asigando del ultimo poi 

            plugin.settings.pois.push(poiData);//Lo agrego a la lista de POIs

            //Guardo los POIs en el objeto de la lista
            var relatedPOis = jQuery.grep(plugin.settings.pois, function (value) {
                return value.relatedFpId == relatedFpId;
            });
            $element.data('POIs', relatedPOis);

            plugin.addPoi(poiData, obj);//Llamo al metodo que lo agrega en la huella
        };

        //POI: Delete POI
        plugin.deletePoi = function (poi) {
            var poiId = poi.data('id');//get POI id
            poi.remove();//Detele POI of screen

            //Search POI on list and remove it
            plugin.settings.pois = jQuery.grep(plugin.settings.pois, function (value) {
                return value.id != poiId;
            });
        }

        //POI: Deletes all POIs on all relations
        plugin.deleteAllPois = function () {
            plugin.settings.pois = new Array;
            plugin.clearAllPois();
        }

        //POI: Deletes all POIs of the selected image
        plugin.deleteAllPoisById = function () {
            $(':data(related-fp-id)').each(function (i, e) {
                plugin.deletePoi($(e));
            });
        }

        //POI: Clears view (does not delete from list)
        plugin.clearAllPois = function () {
            poiWrapper.find('.POI').remove();
        };

        //POI:Restart POIs position data
        function POItransformControl(el) {
            el.data('y', parseInt(el.css('top')));
            el.data('x', parseInt(el.css('left')));

            $.each(plugin.settings.pois, function (indice, valor) {
                if (this.id == el.data('id')) {
                    this.y = el.data('y');
                    this.x = el.data('x');
                }
            });
        };

        /////////////
        //Getters
        /////////////

        //Get Position
        plugin.getPosition = function () {
            return { x: plugin.settings.x, y: plugin.settings.y };
        }

        //Get Zoom
        plugin.getZoom = function () {
            return plugin.settings.zoom;
        }

        //Get Rotation
        plugin.getRotation = function () {
            //Degrees To Radians
            var deg = radiansToDegrees(plugin.settings.rotation);
            return deg;
        }

        //Get Opacity
        plugin.getOpacity = function () {
            return plugin.settings.opacity;
        }

        //Get Brightness
        plugin.getBrightness = function () {
            return plugin.settings.brightness;
        }

        //Get Contrast
        plugin.getContrast = function () {
            return plugin.settings.contrast;
        }

        //Get Invert
        plugin.getInvert = function () {
            return plugin.settings.invert;
        }

        //Get Invert
        plugin.getSaturation = function () {
            return plugin.settings.saturation;
        }

        //Get Saturation
        plugin.getSaturation = function () {
            return plugin.settings.saturation;
        }

        //Get Hue
        plugin.getHue = function () {
            return plugin.settings.hue;
        }

        //Get Z Index
        plugin.getZindex = function () {
            return plugin.settings.zindex;
        }

        //Gets the POIs list
        plugin.getPoiList = function () {
            return plugin.settings.pois;
        }

        //call the "constructor" method
        plugin.init();

    }

    $.fn.EcImageTransformation = function (options) {
        return this.each(function () {
            if (undefined == $(this).data('EcImageTransformation')) {
                var plugin = new $.EcImageTransformation(this, options);
                $(this).data('EcImageTransformation', plugin);
            }
        });
    }

})(jQuery);

/****************/
/******Vars******/
/****************/
var guidesState = false;
var guidesCollection = ['l2', 'l3', 'p1', 'p2', 'p3', 'g2', 'g3', 'e1', 'e2', 'e3'];


/*****************/
/*****Helpers*****/
/*****************/
function getNextColor(colorArr) {
    colorArr.sort(function (a, b) { return a - b; });
    if (colorArr.length >= 1 && colorArr[0] == 1) {
        for (var i = 0; i < colorArr.length; i++) {
            if (colorArr[i + 1] - colorArr[i] != 1) {
                return colorArr[i] + 1;
            }
        }
    }
    else {
        return 1;
    }
}

function constrainscale(e, ui) {
    //uncomment to see how you can set value to constrain size
    if (ui.scalex < 0.1) ui.scalex = 0.1;
    if (ui.scaley < 0.1) ui.scaley = 0.1;
}

//var changeHue = function (obj, sender) {
//    if (!obj.data('hue')) {
//        obj.css('-webkit-filter', 'brightness(3.6) hue-rotate(150deg) invert(0.7) saturate(3.7)');
//        obj.css('filter', 'brightness(3.6) hue-rotate(150deg) invert(0.7) saturate(3.7)');
//        sender.html('<img src="' + rootUrl + 'Images/RC_hueActive.png" alt="Cambiar Coloración"/>');
//        obj.data('hue', true);
//    } else {
//        obj.css('-webkit-filter', 'brightness(1) hue-rotate(0deg) invert(0) saturate(1)');
//        obj.css('filter', 'brightness(1) hue-rotate(0deg) invert(0) saturate(1)');
//        sender.html('<img src="' + rootUrl + 'Images/RC_hueInactive.png" alt="Cambiar Coloración"/>');
//        obj.data('hue', false);
//    }
//};


//Ajusta el tamaño de los controles a la pantalla
function resizeControls(fvSize) {
    var winWidth;
    var minSizeW = 960;
    if ($(window).width() < 960) {
        winWidth = minSizeW;
    } else {
        winWidth = window.innerWidth;
        //winWidth = $(window).innerWidth();
        //winWidth = $('body').innerWidth();        
        //console.log(winWidth);
    }

    $('.FingerContainer').width(winWidth);

    var offsetRealSize;

    if (typeof $('.viewPort') != 'undefined' && $('.viewPort')) {
        $('.viewPort').each(function () {
            offsetRealSize = $(this).outerHeight(true) - $(this).outerHeight();

            var viewPortH = $(window).innerHeight() - $('#options').outerHeight(true) - $('#header').outerHeight(true) - $('#buttonPanel').outerHeight(true) - $('.editor-label').outerHeight(true) - parseInt($('.FingerViewer').css('margin-bottom')) - parseInt($('.FingerViewer').css('margin-top')) - 50;

            if (viewPortH < 569) {
                viewPortH = 569;
            }

            $(this).width(winWidth - $('#OrigenRepliegue').outerWidth(true) - $('#OtroOrigen').outerWidth(true) - 8);
            fingerViewerSize.w = $(this).width() * 0.8;

            $(this).height(viewPortH);

        });
    }
};

var goFullScreen = function (state) {
    if (state) {
        $('.FingerViewer').addClass('fullscreen');
        $('.conflictsContainer ').addClass('fullscreen');

        $('#goFullScreen').html('<img src="' + rootUrl + 'Images/RC_fullScreen_off.png" alt="Pantalla Completa" />');
        $('#SideBarL').hide();
        $('#TabMenu').hide();
        $('#mainButtonPanel').hide();
    } else {
        $('.FingerViewer').removeClass('fullscreen');
        $('.conflictsContainer ').removeClass('fullscreen');

        $('#SideBarL').show();
        $('#TabMenu').show();
        $('#mainButtonPanel').show();
        $('#goFullScreen').html('<img src="' + rootUrl + 'Images/RC_fullScreen_on.png" alt="Pantalla Completa" />');

        if ($('#fpD').position().top > $('.viewPort').height() || $('#fpI').position().top > $('.viewPort').height()) {
            $('#fpD').css('top', 100);
            $('#fpI').css('top', 100);
            $('#fpD').css('left', 100);
            $('#fpI').css('left', $('.viewPort').width() - $('#fpI').width() - 100);
        }

    }

};

/***********************/
/*****Triangulacion*****/
/***********************/

//Resetear posicion de Puntos de Triangulación
function resetRulers() {
    $('#p1').css({ 'top': '300px', 'left': '100px' });
    $('#p2').css({ 'top': '300px', 'left': '200px' });
    $('#p3').css({ 'top': '200px', 'left': '150px' });
    $('#e1').css({ 'top': '300px', 'left': '100px' });
    $('#e2').css({ 'top': '300px', 'left': '200px' });
    $('#e3').css({ 'top': '200px', 'left': '150px' });

    linita('l1', 'p1', 'p2');
    linita('l2', 'p2', 'p3');
    linita('l3', 'p3', 'p1');
    linita('g1', 'e1', 'e2');
    linita('g2', 'e2', 'e3');
    linita('g3', 'e3', 'e1');

    //RetolateRules();
}

function RetolateRules(from) {
    var oy1 = parseFloat($('#p1').css('top'));
    var oy2 = parseFloat($('#p2').css('top'));
    var ox1 = parseFloat($('#p1').css('left'));
    var ox2 = parseFloat($('#p2').css('left'));

    var oy3 = parseFloat($('#p3').css('top'));
    var ox3 = parseFloat($('#p3').css('left'));

    var dy1 = parseFloat($('#e1').css('top'));
    var dy2 = parseFloat($('#e2').css('top'));
    var dx1 = parseFloat($('#e1').css('left'));
    var dx2 = parseFloat($('#e2').css('left'));

    var dy3 = parseFloat($('#e3').css('top'));
    var dx3 = parseFloat($('#e3').css('left'));

    var ol = Math.sqrt(Math.pow(ox2 - ox1, 2) + Math.pow(oy2 - oy1, 2));
    var olP = Math.sqrt(Math.pow(ox3 - ox1, 2) + Math.pow(oy3 - oy1, 2));

    var dl = Math.sqrt(Math.pow(dx2 - dx1, 2) + Math.pow(dy2 - dy1, 2));
    var dlP = Math.sqrt(Math.pow(dx3 - dx1, 2) + Math.pow(dy3 - dy1, 2));
    var propo = dl / ol;
    var propd = ol / dl;

    var angleo = Math.atan2(oy2 - oy1, ox2 - ox1) * -1;
    var angled = Math.atan2(dy2 - dy1, dx2 - dx1) * -1;
    var anglexod = Math.atan2(oy3 - oy1, ox3 - ox1);
    var anglexdo = Math.atan2(dy3 - dy1, dx3 - dx1);

    var anglenO = anglexod - (angled - angleo);
    var anglenD = anglexdo - (angleo - angled);

    //from o position
    if (from === 1) {
        var newLeno = olP * propo;

        var newYo = dy1 + newLeno * Math.sin(anglenO);
        var newXo = dx1 + newLeno * Math.cos(anglenO);

        $('#e3').css('top', newYo);
        $('#e3').css('left', newXo);
    }

    //from d position
    if (from === 2) {
        var newLend = dlP * propd;

        var newYd = oy1 + newLend * Math.sin(anglenD);
        var newXd = ox1 + newLend * Math.cos(anglenD);

        $('#p3').css('top', newYd);
        $('#p3').css('left', newXd);
    }

    refreshLines();
}

function refreshLines() {
    linita('l1', 'p1', 'p2');
    linita('l2', 'p2', 'p3');
    linita('l3', 'p3', 'p1');

    linita('g1', 'e1', 'e2');
    linita('g2', 'e2', 'e3');
    linita('g3', 'e3', 'e1');
}

function showHideLines(p, el) {
    if (guidesState) {
        el.html('<img src="' + rootUrl + 'Images/RC_ocultarGuias.png" alt="Ocultar Guias"/>');
        guidesState = false;
    }
    else {
        el.html('<img src="' + rootUrl + 'Images/RC_verGuias.png" alt="Ver Guias"/>');
        guidesState = true;
    }
    $.each(p, function () {
        $('#' + this).toggle();
    });
}

function linita(line, p1, p2) {
    var $p1 = $('#' + p1);
    var $p2 = $('#' + p2);

    var originX1 = parseInt($p1.css('left'));
    var originY1 = parseInt($p1.css('top'));
    var originX2 = parseInt($p2.css('left'));
    var originY2 = parseInt($p2.css('top'));

    var length = Math.sqrt(Math.pow(originX2 - originX1, 2) + Math.pow(originY2 - originY1, 2));
    var angle = 180 / Math.PI * Math.acos((originY2 - originY1) / length);

    if (originX2 > originX1)
        angle *= -1;

    $('#' + line)
        .css('top', originY1)
        .css('left', originX1)
        .css('height', length)
        .css('-webkit-transform', 'rotate(' + angle + 'deg)')
        .css('-moz-transform', 'rotate(' + angle + 'deg)')
        .css('-o-transform', 'rotate(' + angle + 'deg)')
        .css('-ms-transform', 'rotate(' + angle + 'deg)')
        .css('transform', 'rotate(' + angle + 'deg)');
}

var bottonizer = function (imgActive, imgInactive) {
    var bot = "#" + buttonName;
    var botimg = "#" + buttonName + " img";
    var Img = $(botimg).attr("src");

    if ($(botimg).length == 1) {
        if ($(bot).attr("disabled") == false) {
            Img = Img.substr(0, Img.length - 4) + "_disabled.png";
            $(botimg).attr("src", Img);
            $(bot).attr("disabled", true);
        }
        else {
            Img = Img.substr(0, Img.indexOf("_")) + ".png";
            $(botimg).attr("src", Img);
            $(bot).attr("disabled", false);
        }
    }
    else {
        if (!($(bot).attr("disabled"))) {
            $(bot).attr("disabled", "true");
        }
        else {
            $(bot).attr("disabled", "false");
        }
    }
};

/*************************************/
/***************Helpers***************/
/*************************************/

function multiply(a, b) {
    var r = [], i, j, k, t;
    for (i = 0; i < a.length; i++) {
        for (j = 0; j < b[0].length; j++) {
            t = 0;
            for (k = 0; k < a[0].length; k++) {
                t += a[i][k] * b[k][j];
            }
            r[i] = r[i] || [];
            r[i][j] = t;
        }
    }
    return r;
}

function matrixFromCssString(c) {
    c = c.match(/matrix\(([^\)]+)\)/i)[1].split(',');
    c = [
        [+c[0], +c[2], parseFloat(c[4])],
        [+c[1], +c[3], parseFloat(c[5])],
        [0, 0, 1]
    ];
    return c;
}

function translate(m, tx, ty) {
    return [
        [m[0][0], m[0][1], m[0][2] + tx],
        [m[1][0], m[1][1], m[1][2] + ty],
        [0, 0, 1]
    ];
}

function inverse(m) {// m - transform matrix only (2x3)
    var det = m[0][0] * m[1][1] - m[0][1] * m[1][0];
    // if det = 0 ?
    return [
        [m[1][1] / det, -m[0][1] / det, (m[0][1] * m[1][2] - m[1][1] * m[0][2]) / det],
        [-m[1][0] / det, m[0][0] / det, (m[0][2] * m[1][0] - m[0][0] * m[1][2]) / det],
        [0, 0, 1]
    ];
}

function boundingClientRect(element, transformationMatrix) {
    var points = [
        multiply(transformationMatrix, [[0], [0], [1]]),
        multiply(transformationMatrix, [[element.offsetWidth], [0], [1]]),
        multiply(transformationMatrix, [[0], [element.offsetHeight], [1]]),
        multiply(transformationMatrix, [[element.offsetWidth], [element.offsetHeight], [1]])
    ];

    return {
        left: Math.min(points[0][0][0], points[1][0][0], points[2][0][0], points[3][0][0]),
        top: Math.min(points[0][1][0], points[1][1][0], points[2][1][0], points[3][1][0]),
        right: Math.max(points[0][0][0], points[1][0][0], points[2][0][0], points[3][0][0]),
        bottom: Math.max(points[0][1][0], points[1][1][0], points[2][1][0], points[3][1][0])
    };
}

function getTransformationMatrixBuggy(x) {
    var identity = matrixFromCssString('matrix(1,0,0,1,0,0)'),
        transformationMatrix = identity,
        parentRect, rect, t, c, origin;

    while (x && x !== document.documentElement) {

        t = identity;
        parentRect = x.parentNode && x.parentNode.getBoundingClientRect ? x.parentNode.getBoundingClientRect() : null;
        rect = x.getBoundingClientRect();
        if (parentRect) {
            t = translate(t, rect.left - parentRect.left, rect.top - parentRect.top);
        }

        c = (getComputedStyle(x, null).MozTransform || 'none').replace(/^none$/, 'matrix(1,0,0,1,0,0)');
        c = matrixFromCssString(c);

        origin = getComputedStyle(x, null).MozTransformOrigin || '0 0';
        // Firefox gives 50% 50% when there is no transform!? and pixels (50px 30px) otherwise
        if (origin.indexOf('%') !== -1) {
            origin = '0 0';
        }
        origin = matrixFromCssString('matrix(1,0,0,1,' + origin.split(' ') + ')');

        // transformationMatrix = t * origin * c * origin^-1 * transformationMatrix
        transformationMatrix = multiply(multiply(multiply(multiply(t, origin), c), inverse(origin)), transformationMatrix);

        x = x.parentNode;
    }

    return translate(transformationMatrix, -window.pageXOffset, -window.pageYOffset);
}

function getTransformationMatrix(element) {
    var transformationMatrix = matrixFromCssString('matrix(1,0,0,1,0,0)'),
        x = element,
        rect = element.getBoundingClientRect(element), c, r;
    while (x && x !== document.documentElement) {
        c = getComputedStyle(x, null);
        c = (c.OTransform || c.WebkitTransform || c.msTransform || c.MozTransform || 'none').replace(/^none$/, 'matrix(1,0,0,1,0,0)');
        c = matrixFromCssString(c);

        transformationMatrix = multiply(c, transformationMatrix);

        x = x.parentNode;
    }

    r = boundingClientRect(element, transformationMatrix, 0, 0);

    return translate(transformationMatrix, rect.left - r.left, rect.top - r.top);
}

function detectBuggy() {
    var div = document.createElement('div'), rect, result;
    div.style.cssText = 'width:200px;height:200px;position:fixed;-moz-transform:scale(2);';
    document.body.appendChild(div);
    rect = div.getBoundingClientRect();
    result = !!(getComputedStyle(div, null).MozTransform && (rect.bottom - rect.top < 300));//!
    div.parentNode.removeChild(div);
    return result;
}

var buggy = null;

window.offsetXY = function (event, element) {
    if (buggy === null) {
        buggy = detectBuggy();
    }
    var result = multiply(inverse(buggy ? getTransformationMatrixBuggy(element) : getTransformationMatrix(element)), [[event.clientX], [event.clientY], [1]]);
    return {
        x: result[0][0],
        y: result[1][0]
    };
};

window.getBoundingClientRectX = function (element) {
    if (buggy === null) {
        buggy = detectBuggy();
    }
    return buggy ? boundingClientRect(element, getTransformationMatrixBuggy(element)) : element.getBoundingClientRect();
};