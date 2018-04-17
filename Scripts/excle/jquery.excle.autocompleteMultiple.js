var _ExCleSnippetAutocompleteMultiple = function (w, d, $, undefined) {

    /** 
     * jQuery.excle.
     * @namespace $.excle 
     * @memberof $
     * */
    $.excle = $.excle || {};

    var
        /**
         * AutocompleteMultiple de ExCle
         * @description Metodo de jQuery para generar dicho Snippet.
         * @namespace $.excle.autocompleteMultiple
         * @memberof $.excle 
         * @return {function} Factory 
         */
        _snippet = $.excle.autocompleteMultiple = function (element) {

            if (element instanceof HTMLSelectElement) {

                if (element._ExCleSnippetAutocompleteMultiple) return element._ExCleSnippetAutocompleteMultiple;

                return element._ExCleSnippetAutocompleteMultiple = new _fn.init(element);

            }

            return false;

        },

        /**
         * Valores por defecto
         * @namespace $.excle.autocompleteMultiple.defaults
         * @memberof $.excle.autocompleteMultiple
         */
        _defaults = _snippet.defaults = {},

        _ = {

            /* DEFINO TODAS LAS VARIABLES */
            Init: function (self) {

                self.config.characters = /^[\sa-zA-Z0-9ñÑáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ&]?$/;

                self.filterOptionList;
                self.isOpenList = false;
                self.isOpenFullList = false;
                self.optionListActive;
                self.value = '';
                self.prevValue = '';
                self.events = {};

                if (!(self.config.element instanceof HTMLSelectElement)) throw "El elemento no es un select"

                return _.Create(self);

            },

            /* MANIPULACION DEL DOM Y ESTILO */
            Create: function (self) {

                self.select = self.config.element;
                self.select.className = 'ec-ac-select';
                self.allOptions = self.select.querySelectorAll('option');
                self.optionsWithoutValueAttr = self.select.querySelectorAll('option:not([value]), option[value=""]');
                self.options = Array.prototype.filter.call(self.select.querySelectorAll('option[value]'), function (option) {

                    if (Array.prototype.indexOf.call(self.optionsWithoutValueAttr, option) === -1) return true;

                });

                self.widget = d.createElement('div');
                self.widget.className = 'ec-ac-multiple-widget';

                self.search = d.createElement('div');
                self.search.className = 'ec-ac-search';

                self.list = d.createElement('ul');
                self.list.className = 'ec-ac-list';

                self.tagsContainer = d.createElement('div');
                self.tagsContainer.className = 'ec-ac-tags-container';

                self.count = d.createElement('div');
                self.count.className = 'ec-ac-count';

                self.tagsList = d.createElement('div');
                self.tagsList.className = 'ec-ac-tags-list';

                self.inputContainer = d.createElement('div');
                self.inputContainer.className = 'ec-ac-input-container';

                self.inputText = d.createElement('input');
                self.inputText.setAttribute('type', 'text');

                self.showAll = d.createElement('div');
                self.showAll.className = 'ec-ac-show-all';

                self.widget.appendChild(self.select.parentNode.replaceChild(self.widget, self.select));
                self.widget.appendChild(self.search);
                self.widget.appendChild(self.list);

                self.search.appendChild(self.tagsContainer);
                self.search.appendChild(self.inputContainer);
                self.search.appendChild(self.showAll);

                self.tagsContainer.appendChild(self.count);
                self.tagsContainer.appendChild(self.tagsList);

                self.inputContainer.appendChild(self.inputText);

                self.inputHeigth = self.inputText.offsetHeight;
                self.widget.style.height = self.inputHeigth + 'px';
                self.inputText.className = 'ec-ac-input';
                self.inputText.style.height = '100%';
                self.inputText.placeholder = self.placeholder = self.allOptions[0].innerText || 'Buscar';

                //self.list.style.top = widgetHeigth + 'px';

                self.closeList();
                self.restoreValues();

                return _.Events(self);

            },

            Events: function (self) {

                d.addEventListener('click', self.events.documentClick = function (e) {

                    e.stopPropagation();

                    var element = e.target,
                        parent = element.parentElement;

                    if (parent === self.list || element === self.showAll || element === self.inputText) return;

                    self.closeList();
                    self.widget.className = self.widget.className.replace(/\bfocus\b/g, '').trim();

                });

                self.showAll.addEventListener('click', self.events.showAllClick = function () {

                    if (self.isOpenFullList) return self.closeList();

                    self.widget.className = self.widget.className.replace(/\bfocus\b/g, '').trim();
                    self.widget.className += ' focus';

                    self.openFullList();

                });

                self.inputText.addEventListener('keyup', self.events.inputTextKeyup = function (e) {

                    var key = e.key;

                    self.prevValue = self.value;
                    self.value = self.inputText.value.trim();

                    if (!self.value) return; //self.openFullList(); //self.closeList();
                    else if (self.prevValue != self.value) return self.displayList();

                });

                self.inputText.addEventListener('keydown', self.events.inputTextKeydown = function (e) {

                    var key = e.key;

                    if (key == "Tab") {

                        _.addItemActiveFromOptionList(self);
                        return self.closeList();

                    }

                    else if (key == "Backspace" && self.inputText.value == '') _.removeLastItemSelected(self);
                    else if (key == "ArrowDown" || key == "Down") _.activeNextOptionList(self);
                    else if (key == "ArrowUp" || key == "Up") _.activePrevOptionList(self);
                    else if (key == "Enter") _.addItemActiveFromOptionList(self);

                    else return;

                    e.stopPropagation();
                    e.preventDefault();

                });

                self.inputText.addEventListener('focus', self.events.inputTextFocus = function (e) {

                    self.widget.className += ' focus';

                    //if (!self.inputText.value.trim()) self.openFullList();

                });

                self.inputText.addEventListener('focusout', self.events.inputTextFocusout = function (e) {

                    self.widget.className = self.widget.className.replace(/\bfocus\b/g, '').trim();
                    _.triggerEvent(self, 'focusout');
                });

                //self.inputText.addEventListener('click', function (e) {

                //    //self.widget.className += ' focus';

                //    //if (!self.inputText.value.trim()) self.showAll.click();

                //    //if (!self.inputText.value.trim()) self.openFullList();

                //});

                return self;

            },

            Destroy: function (self) {

                d.removeEventListener('click', self.events.documentClick);
                self.showAll.removeEventListener('click', self.events.showAllClick);
                self.inputText.removeEventListener('keyup', self.events.inputTextKeyup);
                self.inputText.removeEventListener('keydown', self.events.inputTextKeydown);
                self.inputText.removeEventListener('focus', self.events.inputTextFocus);
                self.inputText.removeEventListener('focusout', self.events.inputTextFocusout);

                var select = self.widget.removeChild(self.select);

                self.inputContainer.removeChild(self.inputText);

                self.tagsContainer.removeChild(self.tagsList);
                self.tagsContainer.removeChild(self.count);

                self.search.removeChild(self.showAll);
                self.search.removeChild(self.inputContainer);
                self.search.removeChild(self.tagsContainer);

                self.widget.removeChild(self.list);
                self.widget.removeChild(self.search);

                self.widget.parentNode.replaceChild(select, self.widget);

                return self;

            },

            activeNextOptionList: function (self) {

                if (!self.isOpenList && !self.isOpenFullList) self.openFullList();

                var active = self.list.getElementsByClassName('active')[0];

                if (active) {

                    active.className = active.className.replace(/\bactive\b/, '').trim();
                    active = active.nextSibling;

                } else {

                    active = self.list.firstChild;

                }

                if (active) active.className += ' active';

                _.reloadScroll(self, active);

            },

            activePrevOptionList: function (self) {

                if (!self.isOpenList && !self.isOpenFullList) self.openFullList();

                var active = self.list.getElementsByClassName('active')[0];

                if (active) {

                    active.className = active.className.replace(/\bactive\b/, '').trim();
                    active = active.previousSibling;

                } else {

                    active = self.list.lastChild;

                }

                if (active) active.className += ' active';

                _.reloadScroll(self, active);

            },

            addItemActiveFromOptionList: function (self) {

                var active = self.list.getElementsByClassName('active')[0];

                if (active) active.click();

                self.inputText.focus();

            },

            removeLastItemSelected: function (self) {

                var
                    tagsList = self.tagsList.getElementsByClassName('ec-ac-tag'), tagToRemove;

                if (!tagsList.length) return;

                tagToRemove = tagsList[tagsList.length - 1];

                _.removeItemSelected(self, tagToRemove);

            },

            removeItemSelected: function (self, tag) {

                _.deselectItem(self, tag.referenceOption);
                tag.parentNode.removeChild(tag);

                _.collapseTags(self);

            },

            deselectItem: function (self, option) {
                option.removeAttribute('selected');
                option.selected = false;
                //_.triggerEvent(self, 'change');
            },

            selectItem: function (self, option) {
                option.setAttribute('selected', 'selected');
                option.selected = true;
                //_.triggerEvent(self, 'change');
            },

            triggerEvent: function (self, eventName) {

                if ($ && jQuery && $ === jQuery) $(self.select).trigger(eventName);

                else if ("createEvent" in document) {
                    var evt = document.createEvent("HTMLEvents");
                    evt.initEvent(eventName, false, true);
                    self.select.dispatchEvent(evt);
                }
                else
                    self.select.fireEvent("on" + eventName.toLowerCase());
            },

            addItemSelected: function (self, option) {

                var tag = d.createElement('span'),
                    close = d.createElement('i'),
                    textNode = d.createTextNode(option.textContent || '');

                close.innerText = "\u00D7";

                tag.className = 'ec-ac-tag';
                tag.appendChild(close);
                tag.appendChild(textNode);
                tag.referenceOption = option;
                tag.referenceClose = close;
                //tag.style.height = self.widget.clientHeight + 'px';

                //option.setAttribute('selected','');
                _.selectItem(self, option);

                close.addEventListener('click', tag._removeItemListener = function () {

                    _.removeItemSelected(self, tag);

                });

                self.inputText.value = '';
                self.tagsList.appendChild(tag);

                _.collapseTags(self);

            },


            FilterOptionsList: function (self) {

                var
                    regExp = new RegExp(self.value, 'i'),
                    len = self.options.length,
                    x = 0,
                    option,
                    filterList = [];

                for (; x < len; x += 1) {

                    option = self.options[x];

                    //if(!(option instanceof HTMLOptionElement)) continue;

                    if (option.firstChild && regExp.test(option.firstChild.textContent)) {

                        _.buildOptionList(self, option);

                        filterList.push(option);

                    }

                }

                return filterList;
            },

            buildOptionList: function (self, option) {

                var
                    li = d.createElement('li'),
                    image_c = d.createElement('div'),
                    desc_c = d.createElement('div'),
                    textNode = d.createTextNode(option.textContent || ''),
                    p, img;

                li.className = 'ec-ac-li';
                image_c.className = 'ec-ac-image-container';
                desc_c.className = 'ec-ac-desc-container';

                li.appendChild(image_c);
                li.appendChild(desc_c);
                desc_c.appendChild(textNode);

                if (option.hasAttribute('data-image')) {
                    img = d.createElement('img');
                    img.src = option.getAttribute('data-image');
                    img.alt = 'imagen de la opción:' + option.innerText.trim();
                    image_c.appendChild(img);
                }

                if (option.hasAttribute('data-desc')) {

                    p = d.createElement('span');
                    p.innerText = option.getAttribute('data-desc');
                    desc_c.appendChild(p);

                }

                if (option.selected) li.className += ' selected';

                else
                    li.addEventListener('click', function () {

                        _.addItemSelected(self, option);

                        self.closeList();
                        self.inputText.focus();

                    });

                li.addEventListener('mouseenter', function () {

                    li.className += ' active';

                });

                li.addEventListener('mouseleave', function () {

                    li.className = li.className.replace(/\bactive\b/, '').trim();

                });

                self.list.appendChild(li);

                return li;

            },

            collapseTags: function (self) {

                var
                    containerWidth = self.search.offsetWidth,
                    tags = self.tagsList.getElementsByClassName('ec-ac-tag'),
                    tagsWidth;

                self.tagsContainer.className = self.tagsContainer.className.replace(/\bcollapse\b/g, '').trim();

                if (tags.length == 0) return self.tagsList

                tagsWidth = function () {

                    var list, x, tag, ret;

                    for (
                        ret = x = 0,
                        tag = tags[x];
                        tag instanceof HTMLElement;
                        x += 1,
                        tag = tags[x]
                    ) ret += tag.offsetWidth;

                    return ret;

                }();

                self.porcent = Math.floor((100 / containerWidth) * tagsWidth);

                //console.log(containerWidth - tagsWidth);

                if ((containerWidth - tagsWidth) < 100 || self.porcent >= 80) {

                    self.tagsContainer.className += ' collapse';

                    self.count.style.top = self.inputText.clientHeight / 2 + 'px';
                    self.tagsList.style.top = self.inputText.offsetHeight + 'px';

                }

                self.count.innerHTML = tags.length > 9 ? '+9' : tags.length || '';

            },

            reloadScroll: function (self, active) {

                var active = active || self.list.querySelector('li.selected'),
                    middle,
                    offset;

                if (self.list.scrollHeight <= self.list.offsetHeight) return;

                if (active.length == 0) return self.list.scrollTop = 0;

                middle = (self.list.clientHeight / 2) - (active.clientHeight / 2),
                    offset = function () {
                        var sum = 0,
                            lli = self.list.querySelectorAll('li'),
                            len = lli.length,
                            i = 0,
                            li;

                        for (; i < len; i += 1) {
                            li = lli[i];
                            if (li === active) break;
                            sum += li.offsetHeight;
                        }
                        return sum;
                    }();

                self.list.scrollTop = offset - middle;
            },

        },

        _fn = _snippet.fn = {

            constructor: _snippet,

            init: function (element) {

                this.config = {};
                this.config.element = element;

                try {
                    return _.Init(this);
                } catch (e) {
                    console.error(e);
                }

            },

            displayList: function () {

                this.closeList();

                this.filterOptionList = _.FilterOptionsList(this);

                if (!this.filterOptionList.length) return;

                this.list.className += ' open';
                this.isOpenList = true;
                this.isOpenFullList = false;
                this.list.style.top = this.inputText.offsetHeight + 'px';

                return this;

            },

            closeList: function () {

                this.list.innerHTML = '';
                this.list.className = this.list.className.replace(/\bopen\b/, '').trim();
                this.filterOptionList = null;
                this.isOpenFullList = this.isOpenList = false;

                return this;

            },

            getSelectedOptions: function () {

                if (this.select.selectedOptions) return this.select.selectedOptions;
                else {

                    var x = 0, len = this.options.length, option, ret = [];

                    for (; x < len; x += 1) {

                        option = this.options[x];

                        if (option.selected) ret.push(option);

                    }

                    return ret;

                }

            },

            openFullList: function () {

                if (this.isOpenFullList) return;

                this.closeList();

                for (
                    var
                    x = 0,
                    option = this.options[x];
                    option instanceof HTMLOptionElement;
                    x += 1,
                    option = this.options[x]
                ) _.buildOptionList(this, option);

                this.list.className += ' open';
                this.isOpenList = false;
                this.isOpenFullList = true;
                this.list.style.top = this.inputText.offsetHeight + 'px';

                return this;

            },

            restoreValues: function () {

                this.tagsList.innerHTML = '';

                var x = 0, selectedOptions = this.getSelectedOptions(), len = selectedOptions.length, option;

                for (; x < len; x += 1) {

                    option = selectedOptions[x];

                    _.addItemSelected(this, option);

                }

                return this;

            },

            reload: function () {

                _.Destroy(this);
                return _.Init(this);

            }

        },

        _util = _snippet.util = {},

        eachElements = function (fun) {

            if (fun instanceof Function) {
                for (
                    var x = 0,
                    elements = _snippet.getElements(),
                    element = elements[x];
                    element;
                    x += 1,
                    element = elements[x]
                ) fun.call(element, x);
            }

        };

    _snippet.getElements = function () {

        return d.querySelectorAll('[data-autocomplete][multiple]');

    }

    _snippet.reparse = function () {

        eachElements(function () {

            _snippet(this);

        });

        return _snippet;

    }

    _snippet.reload = function () {

        eachElements(function () {

            if (this._ExCleSnippetAutocompleteMultiple) this._ExCleSnippetAutocompleteMultiple.reload();

            else _snippet(this);

        });

        return _snippet;
    }

    _snippet.prototype = _fn.init.prototype = _fn;

    _snippet.reparse();

    return _snippet;

}(typeof window !== "undefined" ? window : this, document, typeof jQuery !== "undefined" ? jQuery : {});
