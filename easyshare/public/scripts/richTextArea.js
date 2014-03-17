/*
 * Daniel Thalmann
 * fÃ©vrier 2009
 * info@thalmann.li
 */

var JQE_MODE_VIEW = 'view';
var JQE_MODE_HTML = 'html';

jQuery.fn.richtextarea = function(args, attrs) {

	for (i = 0; i < this.length; i++) {
		if ((typeof (args) == 'object' || typeof (args) == 'undefined') && typeof (this[i].richtextarea) == 'undefined') {
			editor = new richtextarea();
			editor.setOptions(args);
			editor.initEditor(this[i]);
		}
		else {
			ret = this[i].richtextarea.execFunc(args, attrs);
		}
	}
	if (typeof (ret) != 'undefined')
		return ret;
	else
		return this;
};

richtextarea = function() {

	this.container = null;
	this.window = null;
	this.document = null;
	this.nodeName = "";
	this.fontFamily = '';
	this.textareaHTML = null;
	this.navigationPath = [];
	this.navBar = null;

	this.options = {
		indentTab: true,
		tabCode: true,
		mode: JQE_MODE_VIEW,
		onChange: function() { },
		toolbar: true,
		navigationPath: false
	};

	this.execFunc = function(func, attr) {

		switch (func) {
			case 'bold':
				this.setBold();
				break;
			case 'italic':
				this.setItalic();
				break;
			case 'switch':
				this.switchMode();
				break;
			case 'gethtml':
				return this.getHtml();
				break;
		}
	};

	this.setOptions = function(newOptions) {
		if (typeof (newOptions) == 'object') {
			/*			if (typeof (newOptions.indentTab) != 'undefined')
			this.options.indentTab = newOptions.indentTab;
			if (typeof (newOptions.tabCode) != 'undefined')
			this.options.tabCode = newOptions.tabCode;
			*/
			if (typeof (newOptions.mode) != 'undefined')
				this.options.mode = newOptions.mode;
			if (typeof (newOptions.onChange) != 'undefined')
				this.options.onChange = newOptions.onChange;
			if (typeof (newOptions.navigationPath) != 'undefined')
				this.options.navigationPath = newOptions.navigationPath;
			if (typeof (newOptions.toolbar) != 'undefined')
				this.options.toolbar = newOptions.toolbar;
		}
	};

	this.initEditor = function(element) {

		this.nodeName = this.getNodeName(element);

		// si c'est un textarea, on le substitue
		if (this.nodeName == 'textarea') {

			// enregistre le lien du textarea
			this.textareaHTML = element;
			element.richtextarea = this;

			RichTextArea = '';
			if (this.options.toolbar) {
				RichTextArea += '<div class="ui-richtextarea-toolsbar ui-widget-header ui-widget-content ui-corner-all" unselectable="on">';
				RichTextArea += '<a href="#" class="ui-richtextarea-button ui-state-default" unselectable="on"><span class="ui-richtextarea-icon-bold ui-richtextarea-icon">Bold</span></a>';
				RichTextArea += '<a href="#" class="ui-richtextarea-button ui-state-default" unselectable="on"><span class="ui-richtextarea-icon-italic ui-richtextarea-icon">Italic</span></a>';
				RichTextArea += '</div>';
			}
			RichTextArea += '<div class="ui-richtextarea-content ui-widget-content">';
			RichTextArea += '</div>';

			if (this.options.navigationPath) {
				RichTextArea += '<div class="ui-richtextarea-navbar ui-corner-all ui-widget-content">';
				RichTextArea += '</div>';
			}

			editor = document.createElement("div");
			// ajoute dans le nouveau div l'interface enrichi
			jQuery(editor).html(RichTextArea);
			jQuery(editor).addClass('ui-widget ui-richtextarea ui-widget-content ui-corner-all');

			jQuery(element).after(editor);

			if (this.options.navigationPath) {
				this.navBar = jQuery(editor).find('.ui-richtextarea-navbar').get(0);
			}

			if (this.options.toolbar) {
				jQuery(editor).find('.ui-richtextarea-button').hover(function() {
					jQuery(this).addClass('ui-state-hover');
				}, function() {
					jQuery(this).removeClass('ui-state-hover');
				});
				jQuery(editor).find('.ui-richtextarea-button').mousedown(function(e) {
					return false;
				});
				jQuery(editor).find('.ui-richtextarea-icon-italic').get(0).richtextarea = this;
				jQuery(editor).find('.ui-richtextarea-icon-italic').click(function() {
					this.richtextarea.setItalic();
				});
				jQuery(editor).find('.ui-richtextarea-icon-bold').get(0).richtextarea = this;
				jQuery(editor).find('.ui-richtextarea-icon-bold').click(function() {
					this.richtextarea.setBold();
				});
			}

			// spÃ©cifie le nouveau container
			element = jQuery(editor).find('.ui-richtextarea-content').get(0);

			jQuery(element).html(jQuery(this.textareaHTML).text());
			
			jQuery(element).height(jQuery(this.textareaHTML).height());
			jQuery(editor).width(jQuery(this.textareaHTML).width());
			/*jQuery(editor).css('overflow','hidden');*/
			
			jQuery(this.textareaHTML).hide();
		}

		element.richtextarea = this;

		this.fontFamily = jQuery(element).css('font-family');
		this.container = element;
		this.window = window;
		this.document = window.document;

		this._activEditMode();
		this._attachEvent();

	};

	// initialise le mode d'Ã©dition
	this._activEditMode = function() {
		// active le mode d'Ã©dition de l'Ã©lÃ©ment
		this.container.designMode = 'On';
		this.container.contentEditable = true;
	};
	// initialise les Ã©vÃ©nements attachÃ© Ã  l'Ã©lÃ©ment
	this._attachEvent = function() {
		jQuery(this.container).keydown(function(event) { return this.richtextarea.onKeyDown(event) });
		jQuery(this.container).dblclick(function(event) { return this.richtextarea.onDblClick(event) });
		jQuery(this.container).mouseup(function(event) { return this.richtextarea.onMouseUp(event) });
		jQuery(this.container).keyup(function(event) { return this.richtextarea.onKeyUp(event) });
	};

	//
	this.onMouseUp = function(e) {
		this.setNavigationPath();
		return true;
	};
	// 
	this.onDblClick = function(e) {

		return true;
	};

	// fonction executÃ© lors d'un changement de text
	this.onChange = function(e) {

		if (this.textareaHTML) {
			jQuery(this.textareaHTML).html(this.HtmlEncode(this.getHtml()));
		}

		if (typeof (this.options.onChange) == 'function') {
			this.options.onChange(e);
		}

		return true;
	};

	// fonction executÃ© lors d'un changement de mode
	this.onSwitchMode = function(e) {

		return true;
	};

	this.onKeyDown = function(e) {

		// obtient le code du caractÃ¨re pressÃ©
		if (window.event) { // IE
			keyNum = e.keyCode;
		}
		else if (e.which) {// Netscape/Firefox/Opera
			keyNum = e.which;
			if (keyNum == 0)
				keyNum = e.keyCode;
		}

		if (this.options.indentTab) {
			if (keyNum == 9) {
				//this.container.focus();
				//this.insertHtml('<span style="margin-right:20px;">&nbsp;</span>', false);
				//this.document.execCommand("indent", false, null);
				//this.insertHtml('&nbsp;&nbsp;&nbsp;&nbsp;', false);
				//this.container.focus();
				this.stopPropagation(e);

				return false;
			}
		}

		if (keyNum == 13) {

			if (jQuery.browser.msie) {
				this.insertHtml("<br/>", true);
				this.stopPropagation(e);

				this.onChange({ 'func': 'onKeyDown', 'event': e });

				return false;

			}
		}

		this.onChange({ 'func': 'onKeyDown', 'event': e });
		return true;
	};

	this.onKeyUp = function(e) {
		this.setNavigationPath();
		this.onChange({ 'func': 'onKeyUp', 'event': e });
	};


	// obtient un objet de selection
	this.getSelection = function() {
		// IE
		if (typeof (this.document.selection) != 'undefined')
			return this.document.selection;
		else
			return this.window.getSelection();
	};

	// retourne un objet textRange pour internet explorer 
	// et un objet Range pour mozilla et autre...
	this.createRange = function(selection) {
		if (typeof (selection) != "undefined") {
			// IE
			if (typeof (selection.createRange) != 'undefined')
				return selection.createRange();
			else {
				if (selection.rangeCount > 0)
					return selection.getRangeAt(0);
				else
					return null;
			}
		}
		else {
			// IE
			if (typeof (this.document.selection) != 'undefined')
				return this.document.selection.createRange();
			else
				return this.document.createRange();
		}
	};

	// rempli la structure des parents de la selection courante.
	this.setNavigationPath = function() {
		if (!this.options.navigationPath)
			return;

		element = this.getParentSelection();
		jQuery(this.navBar).html('');
		this.navigationPath = [];
		i = 0;

		while (element != this.container && typeof (element) != 'undefined' && element.parentNode != null) {
			//go through the elements and build the path
			if (element.parentNode) {
				this.navigationPath[i] = element;
				element = element.parentNode;
				i++;
			}
		}

		// jQuery(this.navBar).append('<a href="#" class="ui-state-default" unselectable="on"><span>Container</span></a>');

		for (i = this.navigationPath.length - 1; i > -1; i--) {
			if (this.navigationPath[i].nodeType != 3) {
				jQuery(this.navBar).append('<a href="#" class="ui-richtextarea-nav' + i + ' ui-state-default" unselectable="on"><span>' + this.getNodeName(this.navigationPath[i]) + '</span></a>');
				navButton = jQuery(this.navBar).find('.ui-richtextarea-nav' + i).get(0);
				navButton.richtextarea = this;
				navButton.selElement = this.navigationPath[i];
				jQuery(navButton).click(function() {
					this.richtextarea.selectElement(this.selElement);
				});
			}
		}
	};

	// obtiens le noeud parent de la selection
	this.getParentSelection = function() {
		element = undefined;
		selection = this.getSelection();
		range = this.createRange(selection);
		// IE
		if (typeof (selection.type) != 'undefined') {
			if (selection.type == "Control") {
				element = range(0).parentNode;
			}
			else {
				element = range.parentElement();
			}
		} else {
			element = range.startContainer;
			if (element.nodeType == 3) {
				if (element.textContent == '') {
					element = element.nextSibling;
				}
			}
		}
		if (element.nodeType == 3) {
			element = element.parentNode;
		}

		return element;

	};

	// selectionne un Ã©lÃ©ment
	this.selectElement = function(element) {

		selection = this.getSelection();
		if (typeof (selection.selectAllChildren) != 'undefined') {
			selection.selectAllChildren(element);
		}
		else {
			// The current selection
			end_range = this.createRange();
			// We'll use this as a 'dummy'
			start_range = end_range.duplicate();
			// Select all text
			start_range.moveToElementText(element);
			// Now move 'dummy' end point to end point of original range
			start_range.setEndPoint('EndToEnd', end_range);
			// Now we can calculate start and end points
			element.selectionStart = start_range.text.length - end_range.text.length;
			element.selectionEnd = element.selectionStart + end_range.text.length;
		}
	};

	// insert un contenu html dans la selection
	this.insertHtml = function(html, selectInsert) {
		if (typeof (selectInsert) == 'undefined')
			selectInsert = true;

		this.container.focus();

		selection = this.getSelection();
		range = this.createRange(selection);

		if (jQuery.browser.msie) {
			this.document.execCommand("delete", false, null);
			range.HTMLText = "";
			range.pasteHTML(html);
			range.collapse(false);

			if (selectInsert) {
				divElement = this.document.createElement("div");
				divElement.innerHTML = html;
				text = divElement.innerText;
				textLength = text.length;
				if (textLength > 0)
					range.moveStart("character", -textLength);
				range.select();
			}
		}
		else {

			range.deleteContents();
			selection.removeAllRanges();

			rangeElement = this.createRange();
			fragment = rangeElement.createContextualFragment(html);
			firstElement = fragment.firstChild;
			if (firstElement.nodeType == 3) {
				if (firstElement.textContent == ' ') {
					fragment.removeChild(firstElement);
					firstElement = fragment.firstChild;
				}
			}
			lastElement = fragment.lastChild;

			nodeStart = range.startContainer;
			offset = range.startOffset;

			switch (nodeStart.nodeType) {
				case 3: // TEXT_NODE
					if (fragment.nodeType == 3) { // TEXT_NODE 
						nodeStart.insertData(offset, fragment.data);
						range = this.createRange();
						range.setEnd(nodeStart, offset + fragmentLength);
						range.setStart(nodeStart, offset + fragmentLength);
						selection.addRange(range);
					}
					else {
						nodeStart = nodeStart.splitText(offset);
						nodeStart.parentNode.insertBefore(fragment, nodeStart);

					}
					break;

				case 1: // ELEMENT_NODE
					nodeStart.insertBefore(fragment, nodeStart.childNodes[offset]);
					break;
			}

			// sÃ©lection l'insertion du code HTML
			//
			if (selectInsert) {
				if (firstElement != null && lastElement.nextSibling != null) {
					range = this.createRange();
					range.setStart(firstElement, 0);
					range.setEnd(lastElement.nextSibling, 0);
					selection.removeAllRanges();
					selection.addRange(range);
				}
			}

		}

		this.setNavigationPath();

	};

	//
	// retourne un fragment de la selection
	this.getSelectedFragment = function() {

		selection = this.getSelection();
		range = this.createRange(selection);

		if (typeof (range.htmlText) != 'undefined') {
			return this.getFragment(range.htmlText);
		}
		else {
			if (range.collapsed) {
				return null;
			}
			divElement = this.document.createElement("div");
			divElement.appendChild(range.cloneContents());

			return divElement;
		}
	};

	//
	// retourne le contenu Html de la selection
	this.getSelectedHtml = function() {

		selection = this.getSelection();
		range = this.createRange(selection);

		if (jQuery.browser.msie) {
			return range.htmlText;
		}
		else {
			if (range.collapsed) {
				return '';
			}
			// return range.toString();

			fragment = range.cloneContents();
			divElement = this.document.createElement("div");
			jQuery(divElement).append(fragment);

			return jQuery(divElement).html();

		}
	};

	this.setStyle2 = function(style, setValues, unsetValue) {

		fragment = this.getSelectedFragment();
		this.setAllStyleStyle(fragment, style, setValues, unsetValue);
		this.insertHtml(jQuery(fragment).html());

	};

	this.setAllStyleStyle = function(fragment, style, setValues, unsetValue) {

		for (i = 0; i < fragment.childNodes.length; i++) {
			//	if (fragment.childNodes[i].childNodes.length > 0) {
			this.setAllStyleStyle(fragment.childNodes[i], style, setValues, unsetValue);
			//	}
		}
		if (fragment.nodeType == 3) {
			myParent = fragment.parentNode;
			if (this.getNodeName(myParent) == 'span') {
				this.setElementStyle(myParent, style, setValues, unsetValue);
			}
			else {
				spanElement = this.document.createElement("span");
				this.setElementStyle(spanElement, style, setValues, unsetValue);
				jQuery(fragment).after(spanElement);
				jQuery(spanElement).append(fragment);
			}
		}

	};

	// aplique un style Ã  un Ã©lÃ©ment ou supprime-le
	this.setElementStyle = function(element, style, setValues, unsetValue) {

		currentValue = new String(jQuery(element).css(style));
		finded = false;

		if (typeof (setValues) == 'object') {
			for (index in setValues) {
				if (currentValue.toLowerCase() == setValues[index].toLowerCase()) {
					finded = true;
					break;
				}
			}
			setValue = setValues[0];
		}
		else {
			if (currentValue.toLowerCase() == setValues.toLowerCase()) {
				finded = true;
			}
			setValue = setValues;
		}
		if (finded) {
			jQuery(element).css(style, unsetValue);
		}
		else {
			jQuery(element).css(style, setValue);
		}
	};


	// applique un style sur un Ã©lÃ©ment span.
	// Si l'Ã©lÃ©ment span n'existe pas, on l'ajoute.
	// Si le style existe avec la mÃªme valeur dÃ©fini, le style est supprimÃ©.
	// Dans le cas ou le style Ã  tester peu prendre plusieurs valeur suivant les navigateurs,
	// on peut donner Ã  la fonction une liste d'Ã©lÃ©ment Ã  tester.
	this.setStyle = function(style, setValues, unsetValue) {

		//this.setStyle2(style, setValues, unsetValue);
		//return;

		html = this.getSelectedHtml();
		parentSelection = this.getParentSelection();
		nodeName = this.getNodeName(parentSelection);
		if (parentSelection) {
			if (parentSelection.nodeType == 3) {
				parentcontent = parentSelection.textContent.toLowerCase();
			} else {
				parentcontent = parentSelection.innerHTML.toLowerCase();
			}
		}

		if (html == '')
			return;

		if (this.outerHTML(parentSelection).toLowerCase() == html.toLowerCase() && nodeName == 'span') {
			//if (nodeName == 'span') {
			this.setElementStyle(parentSelection, style, setValues, unsetValue);
		}
		else {

			if (typeof (setValues) == 'object') {
				this.insertHtml('<span style="' + style + ':' + setValues[0] + ';">' + html + '</span>');
			}
			else {
				this.insertHtml('<span style="' + style + ':' + setValues + ';">' + html + '</span>');
			}
		}
		/*

		if (!jQuery.browser.msie) {
		//supprimer les span vides
		this.clearObsoletSpan();
		}

*/
	};

	this.clearObsoletSpan = function() {
		jQuery(this.container).find("span").each(function() {
			if (jQuery(this).html() == "")
				jQuery(this).remove();
		});
	};

	this.HtmlEncode = function(text) {
		text = new String(text);

		text = text.replace(/&/g, "&amp;");
		text = text.replace(/"/g, "&quot;");
		text = text.replace(/</g, "&lt;");
		text = text.replace(/>/g, "&gt;");
		text = text.replace(/\'/g, '&#39;'); // 39 27
		// text = text.replace(/'/g, "&#146;") ;

		return text;
	};

	this.HtmlDecode = function(text) {
		text = new String(text);

		text = text.replace(/&quot;/g, '"');
		text = text.replace(/&amp;/g, '&');
		text = text.replace(/&#39;/g, "'");
		text = text.replace(/&lt;/g, '<');
		text = text.replace(/&gt;/g, '>');
		return text;
	};

	this.switchMode = function() {

		if (this.options.mode == JQE_MODE_VIEW) {
			this.options.mode = JQE_MODE_HTML;
			this.container.innerHTML = this.HtmlEncode(this.container.innerHTML);
			jQuery(this.container).css('font-family', 'Courier New');

		}
		else {
			this.options.mode = JQE_MODE_VIEW;
			this.container.innerHTML = this.HtmlDecode(this.container.innerHTML);
			jQuery(this.container).css('font-family', this.fontFamily);
		}

		this.onSwitchMode({ 'mode': this.options.mode });

	};

	this.getFragment = function(html) {
		fragment = this.document.createElement("div");
		jQuery(fragment).html(html);
		return fragment;
	};

	// obtient le code html de l'Ã©lÃ©ment
	this.getHtml = function() {

		if (this.options.mode == JQE_MODE_VIEW) {
			return this.container.innerHTML;
		}
		else {
			return jQuery(this.container).text();
		}
	};

	// dÃ©fini le contenu html de l'Ã©lÃ©ment
	this.setHtml = function(value) {

		this.container.innerHTML = value;

		this.onChange({ 'func': 'setHtml' });
	};

	// applique le tag gras sur le texte sÃ©lectionnÃ©
	this.setBold = function() {
		if (this.options.mode == JQE_MODE_HTML)
			return;

		//this.setStyle('font-weight', ['bold', '700'], 'normal');
		this.document.execCommand("bold", false, null);

		this.onChange({ 'func': 'setBold' });
	};

	// applique le tag italique sur le texte sÃ©lectionnÃ©
	this.setItalic = function() {
		if (this.options.mode == JQE_MODE_HTML)
			return;

		//this.setStyle('font-style', 'italic', 'normal');
		this.document.execCommand("italic", false, null);

		this.onChange({ 'func': 'setItalic' });
	};

	this.setColor = function(color) {
		if (this.options.mode == JQE_MODE_HTML)
			return;

		// this.setStyle('color', color, '');
		this.document.execCommand("forecolor", false, color);

		this.onChange({ 'func': 'setColor' });
	};

	// crÃ©e un lien 
	this.setLink = function(link) {
		if (this.options.mode == JQE_MODE_HTML)
			return;

		this.container.focus();
		if (link == "" || !link)
			if (jQuery.browser.msie)
			this.document.execCommand("createlink", true, "");
		else {
			link = window.prompt("Url", "http://");
			this.document.execCommand("createlink", false, link);
		}
		else
			this.document.execCommand("createlink", false, link);
		this.onChange({ 'func': 'setLink', 'link': link });
	};

	this.deleteSelection = function() {
		this.document.execCommand("delete", false, null);
		this.onChange({ 'func': 'deleteSelection' });
	};

	this.setIndent = function() {
		if (this.options.mode == JQE_MODE_HTML)
			return;

		this.document.execCommand("indent", false, null);
		this.onChange({ 'func': 'setIndent' });
	};

	this.setFontSize = function(size) {
		if (this.options.mode == JQE_MODE_HTML)
			return;

		//		if(jQuery.browser.msie)
		this.setStyle('font-size', size, 'medium');
		//		else
		//			this.document.execCommand("fontsize", false, size);

		this.onChange({ 'func': 'setFontSize', 'size': size });
	};

	this.removeFormat = function() {
		if (this.options.mode == JQE_MODE_HTML)
			return;

		if (jQuery.browser.msie) {
			divElement = this.document.createElement("div");
			jQuery(divElement).html(this.getSelectedHtml());

			jQuery(divElement).find("span").each(function() {
				jQuery(this).attr('style', ' ');
			});

			this.insertHtml(jQuery(divElement).html());
			this.clearObsoletSpan();
		}
		else {
			this.document.execCommand("removeformat", false, null);
		}
		this.onChange({ 'func': 'removeFormat' });
	};

	this.stopPropagation = function(e) {
		if (!e) {
			e = window.event;
			e.cancelBubble = true;
		}
		if (e.stopPropagation)
			e.stopPropagation();
	};

	this.getNodeName = function(element) {

		if (typeof (element.tagName) != 'undefined')
			return element.tagName.toLowerCase();
		else
			return element.nodeName.toLowerCase();
	};

	// retourne le code htm d'un Ã©lÃ©ment avec son propre Tag
	this.outerHTML = function(element) {
		if (typeof (element.outerHTML) != 'undefined') {
			return element.outerHTML;
		}
		else {
			divElement = this.document.createElement("div");
			newElement = element.cloneNode(true);
			jQuery(divElement).append(newElement);

			return jQuery(divElement).html();
		}

	};

};