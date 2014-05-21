/**
 * Created with JetBrains PhpStorm.
 * User: Andy
 * Date: 8/12/12
 * Time: 10:12
 * To change this template use File | Settings | File Templates.
 */
window.onload = function () {
    var _uBar = {
        load:function () {
            $('[rel="tooltip"]').tooltip();
            $('#_user').submit(function (e) {
                var form = $('#_user');
                form.toggleClass('muted');
                $('.icon-arrow-22').attr('class', 'icon-loading icon-white');
                data = form.serialize().replace('_target_path', 'ajax');
                $.ajax({
                    url:form.attr('action'),
                    type:'POST',
                    data:data,
                    success:function (response) {
                        $('#_user-bar').html(response);
                        _uBar.load();
                    },
                    error:function () {
                        form.toggleClass('muted');
                        $('.icon-loading').attr('class', 'icon-arrow-22 icon-white');
                        form.attr('rel', "tooltip");
                        form.attr('data-original-title', "Connection Error!");
                        form.attr('data-placement', "left");
                        form.tooltip();
                    }
                });
                e.preventDefault();
            });
            $('#logout').click(function (e) {
                var uBar = $('#user-bar');
                uBar.toggleClass('muted');
                $('.icon-exit').attr('class', 'icon-loading icon-white');
                $.ajax({
                    url:$('#logout').attr('href'),
                    type:'GET',
                    success:function (response) {
                        $('#_user-bar').html(response);
                        _uBar.load();
                    },
                    error:function () {
                        uBar.toggleClass('muted');
                        $('.icon-loading').attr('class', 'icon-exit icon-white');
                        uBar.attr('rel', "tooltip");
                        uBar.attr('data-original-title', "Connection Error!");
                        uBar.attr('data-placement', "left");
                        uBar.tooltip();
                    }
                });
                e.preventDefault();
            });
            $('#register').click(function (e) {
                var form = $('#_user');
                form.toggleClass('muted');
                $('.icon-contact').attr('class', 'icon-loading icon-white');
                $.ajax({
                    url:$('#register').attr('href'),
                    type:'GET',
                    success:function (response) {
                        _uBar.modal(response);
                        form.toggleClass('muted');
                        $('.icon-loading').attr('class', 'icon-contact icon-white');
                    },
                    error:function () {
                        form.toggleClass('muted');
                        $('.icon-loading').attr('class', 'icon-contact icon-white');
                        form.attr('rel', "tooltip");
                        form.attr('data-original-title', "Connection Error!");
                        form.attr('data-placement', "left");
                        form.tooltip();
                    }
                });
                e.preventDefault();
            });
            $('#profile').click(function (e) {
                var uBar = $('#user-bar');
                uBar.toggleClass('muted');
                $('.icon-user').attr('class', 'icon-loading icon-white');
                $.ajax({
                    url:$('#profile').attr('href'),
                    type:'GET',
                    success:function (response) {
                        _uBar.modal(response);
                        uBar.toggleClass('muted');
                        $('.icon-loading').attr('class', 'icon-user icon-white');
                    },
                    error:function () {
                        uBar.toggleClass('muted');
                        $('.icon-loading').attr('class', 'icon-user icon-white');
                        uBar.attr('rel', "tooltip");
                        uBar.attr('data-original-title', "Connection Error!");
                        uBar.attr('data-placement', "left");
                        uBar.tooltip();
                    }
                });
                e.preventDefault();
            });
        },
        modal:function (html) {
            $('#_utils').html(html);
            $('[rel="tooltip"]').tooltip();
            var myModal = $('#myModal');
            myModal.modal();
            $('#_create').submit(function (e) {
                var myModal = $('#myModal'),
                    form = $('#_create'),
                    data = form.serialize();
                myModal.toggleClass('muted');
                $('.icon-arrow-22').attr('class', 'icon-loading icon-white');
                $.ajax({
                    url:form.attr('action'),
                    type:'POST',
                    data:data,
                    success:function (response) {
                        if (response.split('\n')[0] == "<!DOCTYPE html>")
                            return window.location = $('#_target_url').attr('value');
                        myModal.modal('hide');
                        myModal.toggleClass('muted');
                        $('.icon-loading').attr('class', 'icon-user icon-white');
                        _uBar.modal(response);
                        $(".alert").alert();
                        $("#modal-error").toggleClass('hide');
                    },
                    error:function () {
                        myModal.toggleClass('muted');
                        $('.icon-loading').attr('class', 'icon-arrow-22 icon-white');
                        $(".alert").alert();
                        $("#modal-error").toggleClass('hide');
                    }
                });
                e.preventDefault();
            });
        }
    };
    _uBar.load();
};
