﻿/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('forms', {
    init: function (a) {
        var b = a.lang;
        a.addCss('form{border: 1px dotted #FF0000;padding: 2px;}');
        var c = function (e, f, g) {
            a.addCommand(f, new CKEDITOR.dialogCommand(f));
            a.ui.addButton(e, {label: b.common[e.charAt(0).toLowerCase() + e.slice(1)], command: f});
            CKEDITOR.dialog.add(f, g);
        }, d = this.path + 'dialogs/';
        c('Form', 'form', d + 'form.js');
        c('Checkbox', 'checkbox', d + 'checkbox.js');
        c('Radio', 'radio', d + 'radio.js');
        c('TextField', 'textfield', d + 'textfield.js');
        c('Textarea', 'textarea', d + 'textarea.js');
        c('Select', 'select', d + 'select.js');
        c('Button', 'button', d + 'button.js');
        c('ImageButton', 'imagebutton', CKEDITOR.plugins.getPath('image') + 'dialogs/image.js');
        c('HiddenField', 'hiddenfield', d + 'hiddenfield.js');
        if (a.addMenuItems) a.addMenuItems({
            form: {label: b.form.menu, command: 'form', group: 'form'},
            checkbox: {label: b.checkboxAndRadio.checkboxTitle, command: 'checkbox', group: 'checkbox'},
            radio: {label: b.checkboxAndRadio.radioTitle, command: 'radio', group: 'radio'},
            textfield: {label: b.textfield.title, command: 'textfield', group: 'textfield'},
            hiddenfield: {label: b.hidden.title, command: 'hiddenfield', group: 'hiddenfield'},
            imagebutton: {label: b.image.titleButton, command: 'imagebutton', group: 'imagebutton'},
            button: {label: b.button.title, command: 'button', group: 'button'},
            select: {label: b.select.title, command: 'select', group: 'select'},
            textarea: {label: b.textarea.title, command: 'textarea', group: 'textarea'}
        });
        if (a.contextMenu) {
            a.contextMenu.addListener(function (e) {
                if (e && e.hasAscendant('form')) return {form: CKEDITOR.TRISTATE_OFF};
            });
            a.contextMenu.addListener(function (e) {
                if (e) {
                    var f = e.getName();
                    if (f == 'select') return {select: CKEDITOR.TRISTATE_OFF};
                    if (f == 'textarea') return {textarea: CKEDITOR.TRISTATE_OFF};
                    if (f == 'input') {
                        var g = e.getAttribute('type');
                        if (g == 'text' || g == 'password') return {textfield: CKEDITOR.TRISTATE_OFF};
                        if (g == 'button' || g == 'submit' || g == 'reset') return {button: CKEDITOR.TRISTATE_OFF};
                        if (g == 'checkbox') return {checkbox: CKEDITOR.TRISTATE_OFF};
                        if (g == 'radio') return {radio: CKEDITOR.TRISTATE_OFF};
                        if (g == 'image') return {imagebutton: CKEDITOR.TRISTATE_OFF};
                    }
                    if (f == 'img' && e.getAttribute('_cke_real_element_type') == 'hiddenfield') return {hiddenfield: CKEDITOR.TRISTATE_OFF};
                }
            });
        }
    }, requires: ['image']
});
if (CKEDITOR.env.ie) CKEDITOR.dom.element.prototype.hasAttribute = function (a) {
    var d = this;
    var b = d.$.attributes.getNamedItem(a);
    if (d.getName() == 'input') switch (a) {
        case 'class':
            return d.$.className.length > 0;
        case 'checked':
            return !!d.$.checked;
        case 'value':
            var c = d.getAttribute('type');
            if (c == 'checkbox' || c == 'radio') return d.$.value != 'on';
            break;
        default:
    }
    return !!(b && b.specified);
};
