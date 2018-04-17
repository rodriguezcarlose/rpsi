var _ExCleSnippetAutocomplete = function (w, d, $, undefined) {

    $.excle = $.excle || {};

    var
        _snippet = $.excle.autocomplete = function(element) {

            if (element instanceof HTMLSelectElement) {

                if (element._ExCleSnippetAutocomplete) return element._ExCleSnippetAutocomplete;

                return element._ExCleSnippetAutocomplete = new _fn.init(element);

            }

            return false;

        },

        _defaults = _snippet.defaults = {
            element: ''
        },

        _ = {
            Init: function(self) {

                self.filterList;
                self.prevFilterList;
                self.prevValue;
                self.value;
                self.listBuilt;
                self.ListisOpen;
                self.FullListisOpen;
                self.keyUpTimeOut;
                self.events = {};

                self.config.characters = /^[\sa-zA-Z0-9ñÑáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ&]?$/;

                if (!(self.config.element instanceof HTMLSelectElement)) throw "El elemento no es un select";

                return _.Create(self);

            },

            Create: function(self) {

                // DOM
                self.select = self.config.element;
                self.select.className = 'ec-ac-select';
                self.optionsWithoutValueAttr = self.select.querySelectorAll('option:not([value]), option[value=""]');
                self.options = Array.prototype.filter.call(self.select.options, function(option) {

                    if (Array.prototype.indexOf.call(self.optionsWithoutValueAttr, option) === -1) return true;

                });

                //end

                self.widget = d.createElement('div');
                self.widget.className = 'ec-ac-widget';

                self.inputText = d.createElement('input');
                self.inputText.setAttribute('type', 'text');
                self.inputText.className = 'ec-ac-search';

                self.list = d.createElement('ul');
                self.list.className = 'ec-ac-list';

                self.showAll = d.createElement('div');
                self.showAll.className = 'ec-ac-show-all';

                self.clear = d.createElement('div');
                self.clear.className = 'ec-ac-clear';

                self.widget.appendChild(self.select.parentNode.replaceChild(self.widget, self.select));
                self.widget.appendChild(self.inputText);
                self.widget.appendChild(self.clear);
                self.widget.appendChild(self.showAll);
                self.widget.appendChild(self.list);

                self.selectedOption = self.getSelectedOption();
                self.optionValue = self.selectedOption ? self.selectedOption.label : '';
                self.list.style.top = self.inputText.offsetHeight + 'px';
                self.inputText.placeholder = self.placeholder = self.select.options[0].label || 'Buscar';

                self.closeList(true);
                self.restoreValues();

                return _.Events(self);
            },

            Events: function(self) {

                d.addEventListener('click', self.events.documentClick = function(e) {

                    e.stopPropagation();

                    var element = e.target,
                        parent = element.parentElement;

                    if (parent === self.list || element === self.showAll || element === self.inputText) return;

                    self.closeList();
                    self.widget.className = self.widget.className.replace(/\bfocus\b/g, '').trim();

                });

                self.showAll.addEventListener('click', self.events.showAllClick = function() {

                    if (self.isOpenFullList) return self.closeList();

                    self.widget.className = self.widget.className.replace(/\bfocus\b/g, '').trim();
                    self.widget.className += ' focus';

                    self.openFullList();

                });

                self.inputText.addEventListener('keyup', self.events.inputTextKeyup = function(e) {

                    self.prevValue = self.value;
                    self.value = self.inputText.value.trim();

                    if (!self.value) return _.removeItemSelected(self); //self.openFullList(); //self.closeList();
                    else if (self.prevValue != self.value) return self.openList();

                });

                self.inputText.addEventListener('keydown', self.events.inputTextKeydown = function(e) {

                    var key = e.key;

                    if (key == "Tab") {

                        _.addItemActiveFromOptionList(self);
                        return self.closeList();

                    } else if (key == "ArrowDown" || key == "Down") _.activeNextOptionList(self);
                    else if (key == "ArrowUp" || key == "Up") _.activePrevOptionList(self);
                    else if (key == "Enter") _.addItemActiveFromOptionList(self);

                    else return;

                    e.stopPropagation();
                    e.preventDefault();

                });

                self.inputText.addEventListener('focus', self.events.inputTextFocus = function(e) {

                    self.widget.className += ' focus';

                    //if (!self.inputText.value.trim()) self.openFullList();

                });

                self.inputText.addEventListener('focusout', self.events.inputTextFocusout = function(e) {

                    self.widget.className = self.widget.className.replace(/\bfocus\b/g, '').trim();
                    self.inputText.value = self.optionValue;
                    _.triggerEvent(self, 'focusout');

                });

                self.clear.addEventListener('click', self.events.clearClick = function() {

                    self.inputText.value = '';

                    _.removeItemSelected(self);

                });

                return self;

            },

            Destroy: function(self) {

                d.removeEventListener('click', self.events.documentClick);
                self.showAll.removeEventListener('click', self.events.showAllClick);
                self.inputText.removeEventListener('keyup', self.events.inputTextKeyup);
                self.inputText.removeEventListener('keydown', self.events.inputTextKeydown);
                self.inputText.removeEventListener('focus', self.events.inputTextFocus);
                self.inputText.removeEventListener('focusout', self.events.inputTextFocusout);
                self.clear.removeEventListener('click', self.events.clearClick);

                var select = self.widget.removeChild(self.select);
                self.widget.removeChild(self.inputText);
                self.widget.removeChild(self.clear);
                self.widget.removeChild(self.showAll);
                self.widget.removeChild(self.list);
                self.widget.parentNode.replaceChild(select, self.widget);

                return self;

            },

            activeNextOptionList: function(self) {

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

            activePrevOptionList: function(self) {

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

            addItemActiveFromOptionList: function(self) {

                var active = self.list.getElementsByClassName('active')[0];

                if (active) active.click();

                self.inputText.focus();

            },

            removeItemSelected: function(self, option) {

                var option = option || self.getSelectedOption();

                if (option) {
                    _.deselectItem(self, option);
                    self.optionValue = '';
                }

            },

            deselectItem: function(self, option) {
                option.removeAttribute('selected');
                option.selected = false;
            },

            selectItem: function(self, option) {
                option.setAttribute('selected', 'selected');
                option.selected = true;
            },

            triggerEvent: function(self, eventName) {

                if ($ && jQuery && $ === jQuery) $(self.select).trigger(eventName);

                else if ("createEvent" in document) {
                    var evt = document.createEvent("HTMLEvents");
                    evt.initEvent(eventName, false, true);
                    self.select.dispatchEvent(evt);
                } else
                    self.select.fireEvent("on" + eventName.toLowerCase());
            },

            addItemSelected: function(self, option) {

                _.removeItemSelected(self);
                _.selectItem(self, option);
                self.inputText.value = self.optionValue = option.label || '';

            },

            reloadScroll: function(self, active) {

                var active = active || self.list.querySelector('li.selected'),
                    middle,
                    offset;

                if (self.list.scrollHeight <= self.list.offsetHeight) return;

                if (!active || active.length == 0) return self.list.scrollTop = 0;

                middle = (self.list.clientHeight / 2) - (active.clientHeight / 2),
                    offset = function() {
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

            FilterOptionsList: function(self) {

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

            buildOptionList: function(self, option) {

                var
                    li = d.createElement('li'),
                    image_c = d.createElement('div'),
                    desc_c = d.createElement('div'),
                    textNode = d.createTextNode(option.textContent || ''),
                    p,
                    img;

                li.className = 'ec-ac-li';
                image_c.className = 'ec-ac-image-container';
                desc_c.className = 'ec-ac-desc-container';

                li.appendChild(image_c);
                li.appendChild(desc_c);
                desc_c.appendChild(textNode);

                if (option.hasAttribute('data-image')) {
                    img = d.createElement('img');
                    img.src = option.getAttribute('data-image');
                    img.alt = 'imagen de la opción:' + option.label.trim();
                    image_c.appendChild(img);
                }

                if (option.hasAttribute('data-desc')) {

                    p = d.createElement('span');
                    p.innerText = option.getAttribute('data-desc');
                    desc_c.appendChild(p);

                }

                if (option.selected) li.className += ' selected';

                else
                    li.addEventListener('click', function() {

                        _.addItemSelected(self, option);

                        self.closeList();
                        self.inputText.focus();

                    });

                li.addEventListener('mouseenter', function() {

                    li.className += ' active';

                });

                li.addEventListener('mouseleave', function() {

                    li.className = li.className.replace(/\bactive\b/, '').trim();

                });

                self.list.appendChild(li);

                return li;

            },

        },

        _fn = _snippet.fn = {
            constructor: _snippet,

            init: function(element) {

                this.config = {};
                this.config.element = element;

                return _.Init(this);

            },

            openList: function() {

                this.closeList();

                if (!this.value || this.value === this.optionValue || this.value === this.prevValue) return;

                this.filterOptionList = _.FilterOptionsList(this);

                var len = this.filterOptionList.length;

                if (len === 0 || (len === 1 && this.filterOptionList[0].value === this.select.value)) return;

                this.list.className += ' open';
                this.isOpenList = true;
                this.isOpenFullList = false;
                this.list.style.top = this.widget.offsetHeight + 'px';

                return this;
            },

            openFullList: function() {

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
                this.list.style.top = this.widget.offsetHeight + 'px';

                return this;
            },

            closeList: function(force) {

                this.list.innerHTML = '';
                this.list.className = this.list.className.replace(/\bopen\b/, '').trim();
                this.filterOptionList = null;
                this.isOpenFullList = this.isOpenList = false;

                return this;
            },

            getSelectedOption: function() {

                var x = 0, len = this.options.length, option, ret;

                for (; x < len; x += 1) {

                    option = this.options[x];

                    if (option.selected) {
                        ret = option;
                        break;
                    }

                }

                return ret;

            },

            restoreValues: function() {

                var option = this.getSelectedOption();

                if (option) _.addItemSelected(this, option);

                return this;

            },

            reload: function() {

                _.Destroy(this);
                return _.Init(this);

            }

        },

        eachElements = function( fun ) {

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

        return d.querySelectorAll('[data-autocomplete]:not([multiple])');

    }

    _snippet.reparse = function () {

        eachElements(function () {

            _snippet(this);

        });

        return _snippet;

    }

    _snippet.reload = function () {

        eachElements(function () {

            if (this._ExCleSnippetAutocomplete) this._ExCleSnippetAutocomplete.reload();

            else _snippet(this);

        });

        return _snippet;
    }

    _snippet.prototype = _fn.init.prototype = _fn;

    _snippet.reparse();

}(typeof window !== "undefined" ? window : this, document, typeof jQuery !== "undefined" ? jQuery : {});
