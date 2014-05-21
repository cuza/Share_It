/* ============================================================
 * bootstrap-contextmenu.js
 * http://
 * ============================================================
 * Copyright 2012 Nikolai Fedotov
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */
!function ($) {
    "use strict"; // jshint ;_;
    /* CONTEXTMENU CLASS DEFINITION
     * ========================= */
    var ContextMenu = function (element) {
        $(element).on('contextmenu.context-menu.data-api', this.show);
        $('html').on('click.context-menu.data-api', clearMenus)
    };
    ContextMenu.prototype = {
        constructor:ContextMenu, show:function (e) {
            var $this = $(this);
            if ($this.is('.disabled, :disabled')) return;
            clearMenus();
            $($this.data('context-menu'))
                .data('e', e)
                .css('position', 'fixed')
                .css('left', e.clientX)
                .css('top', e.clientY - 100)
                .css('display', 'block');
            return false;
        }
    };
    function clearMenus() {
        $('.context-menu')
            .css('display', 'none')
            .data('e', undefined)
    }

    /* CONTEXTMENU PLUGIN DEFINITION
     * ========================== */
    $.fn.contextmenu = function (option) {
        return this.each(function () {
            var $this = $(this)
            if (!$this.data('context-menu-obj')) $this.data('context-menu-obj', new ContextMenu(this))
        })
    };
    $.fn.contextmenu.Constructor = ContextMenu
}(window.jQuery);
var error = function () {
    $('#_util').html('');
    var menu = $('#menu-modal'),
        alert = menu.find(".alert");
    menu.modal();
    alert.show();
    alert.alert();
};
var modal = function (html) {
    $('#_util').html(html);
    $('[rel="tooltip"]').tooltip();
    var menu = $('#menu-modal');
    menu.find(".alert").hide();
    menu.modal();
    if (menu.find('[type="file"]').length == 0)
        menu.find('form').submit(function (e) {
            var menu = $('#menu-modal'),
                form = menu.find('form'),
                data = form.serialize();
            menu.toggleClass('muted');

            $.ajax({
                url:form.attr('action'),
                type:'POST',
                data:data,
                success:function (response) {
                    if (response.split('\n')[0] != "<!-- modal form -->")
                        return window.location = $('#_storage_url').attr('value');
                    $(".alert").alert();
                    $("#modal-error").toggleClass('hide');
                },
                error:function () {
                    menu.toggleClass('muted');
                    $(".alert").alert();
                    $("#modal-error").toggleClass('hide');
                }
            });
            e.preventDefault();
        });
};
var onClick = function (e) {
        var $m = $(this).closest('.context-menu'),
            target = $m.data('e').target;
        while (!target.attributes.hasOwnProperty('data-id'))
            target = target.parentNode;
        var path = e.target.href + "/" + target.attributes.getNamedItem('data-id').nodeValue;
        $.ajax({
            url:path,
            success:modal,
            error:error
        });
        e.preventDefault();
    },
    go = function (e) {
        var $m = $(this).closest('.context-menu'),
            target = $m.data('e').target;
        while (!target.attributes.hasOwnProperty('data-id'))
            target = target.parentNode;
        e.target.href = e.target.href + "/" + target.attributes.getNamedItem('data-id').nodeValue;
    };
$(function () {
    $('#folder-browser').find('a').contextmenu();
    $('#file-browser').find('a').contextmenu();
    $('#folder-menu').find('a').click(onClick);
    $('#file-menu').find('a[data-menu*="true"]').click(onClick);
    $('#file-menu').find('a[data-menu*="go"]').click(go);
});