'use strict';
(function ($) {
    $.fn.enhancedUploader = function () {
        return this.each(function () {
            var me = $(this);
            var $container = $(me).parent();
            function enableButton(button) {
                button.prop('disabled', false).removeClass('disabled');
            }
            function disableButton(button) {
                button.prop('disabled', true).addClass('disabled');
            }
            function displayMessageInQueue(n) {
                var msg = '';
                if (n == 1) {
                    msg = dotclear.jsUpload.msg.file_in_queue;
                } else if (n > 1) {
                    msg = dotclear.jsUpload.msg.files_in_queue;
                    msg = msg.replace(/%d/, n);
                } else {
                    msg = dotclear.jsUpload.msg.no_file_in_queue;
                }
                $('.queue-message', me).html(msg);
            }
            $('.button.choose_files').click(function (e) {
                if ($container.hasClass('enhanced_uploader')) {
                    $('#upfile').click();
                    e.preventDefault();
                }
            });
            $('.button.cancel', '#fileupload .fileupload-buttonbar').click(function () {
                $('.button.cancel', '#fileupload .fileupload-buttonbar').hide();
                disableButton($('.button.start', '#fileupload .fileupload-buttonbar'));
                displayMessageInQueue(0);
            });
            $(me).on('click', '.cancel', function () {
                if ($('.fileupload-ctrl .files .template-upload', me).length == 0) {
                    $('.button.cancel', '#fileupload .fileupload-buttonbar').hide();
                    disableButton($('.button.start', '#fileupload .fileupload-buttonbar'));
                }
                displayMessageInQueue($('.files .template-upload', me).length);
            });
            $('.button.clean', me).click(function (e) {
                $('.fileupload-ctrl .files .template-download', me).slideUp(500, function () {
                    $(this).remove();
                });
                $(this).hide();
                e.preventDefault();
            });
            $(me).fileupload({url: $(me).attr('action'), autoUpload: false, sequentialUploads: true, uploadTemplateId: null, downloadTemplateId: null, uploadTemplate: template_upload, downloadTemplate: template_download}).bind('fileuploadadd', function () {
                $('.button.cancel').css('display', 'inline-block');
                $('#fileupload .fileupload-buttonbar').show();
                enableButton($('.button.start', '#fileupload .fileupload-buttonbar'));
            }).bind('fileuploadadded', function () {
                displayMessageInQueue($('.files .template-upload', me).length);
            }).bind('fileuploaddone', function (e, data) {
                if (data.result.files[0].html !== undefined) {
                    $('.media-list .files-group').append(data.result.files[0].html);
                    $('#form-medias .hide').removeClass('hide');
                }
                $('.button.clean').css('display', 'inline-block');
                $(me).show();
            }).bind('fileuploadalways', function () {
                displayMessageInQueue($('.files .template-upload', me).length);
                if ($('.fileupload-ctrl .files .template-upload', me).length == 0) {
                    $('.button.cancel', '#fileupload .fileupload-buttonbar').hide();
                    disableButton($('.button.start', '#fileupload .fileupload-buttonbar'));
                }
            });
            var $msg, label;
            if ($container.hasClass('enhanced_uploader')) {
                $msg = dotclear.msg.enhanced_uploader_disable;
                label = dotclear.jsUpload.msg.choose_files;
                $(me).fileupload({disabled: false});
                displayMessageInQueue(0);
                disableButton($('.button.start', '#fileupload .fileupload-buttonbar'));
            } else {
                $msg = dotclear.msg.enhanced_uploader_activate;
                label = dotclear.jsUpload.msg.choose_file;
            }
            $('<p class="clear"><button type="button" class="enhanced-toggle">' + $msg + '</button></p>').click(function (e) {
                if ($container.hasClass('enhanced_uploader')) {
                    $msg = dotclear.msg.enhanced_uploader_activate;
                    label = dotclear.jsUpload.msg.choose_file;
                    $('#upfile').attr('multiple', false);
                    enableButton($('.button.start', '#fileupload .fileupload-buttonbar'));
                    $('.files .upload-file', me).remove();
                    $('.button.cancel,.button.clean', '#fileupload .fileupload-buttonbar').hide();
                    $(me).fileupload({disabled: true});
                    $('.queue-message', me).html('').hide();
                } else {
                    $msg = dotclear.msg.enhanced_uploader_disable;
                    label = dotclear.jsUpload.msg.choose_files;
                    $('#upfile').attr('multiple', true);
                    var startButton = $('.button.start');
                    var buttonBar = $('#fileupload .fileupload-buttonbar');
                    disableButton(startButton);
                    disableButton(buttonBar);
                    startButton.css('display', 'inline-block');
                    buttonBar.show();
                    $(me).fileupload({disabled: false});
                    $('.queue-message', me).show();
                    displayMessageInQueue(0);
                }
                $(this).find('button').text($msg);
                $('.add-label', me).text(label);
                $container.toggleClass('enhanced_uploader');
                e.preventDefault();
            }).appendTo(me);
        });
    };
})(jQuery);
$(function () {
    $('#fileupload').enhancedUploader();
    $('.checkboxes-helpers').each(function () {
        dotclear.checkboxesHelpers(this, undefined, '#form-medias input[type="checkbox"]', '#form-medias #delete_medias');
    });
    dotclear.condSubmit('#form-medias input[type="checkbox"]', '#form-medias #delete_medias');
    $('#form-medias #delete_medias').click(function (e) {
        var count_checked = $('input[name="medias[]"]:checked', $('#form-medias')).length;
        if (count_checked == 0) {
            e.preventDefault();
            return false;
        }
        return window.confirm(dotclear.msg.confirm_delete_medias.replace('%d', count_checked));
    });
    $('.modal-image').magnificPopup({type: 'image'});
    $('#form-medias').on('click', '.media-item .attach-media', function (e) {
        var parts = $(this).prop('href').split('?');
        var str_params = parts[1].split('&');
        var postData = {};
        for (var n = 0; n < str_params.length; n++) {
            var kv = str_params[n].split('=');
            postData[kv[0]] = kv[1];
        }
        postData.xd_check = dotclear.nonce;
        $.post(parts[0], postData, function (data) {
            if (data.url !== undefined) {
                document.location = data.url;
            }
        });
        e.preventDefault();
    });
    fileRemoveAct();
    function fileRemoveAct() {
        $('body').on('click', 'a.media-remove', function () {
            var m_name = $(this).parents('.media-item').find('a.media-link').text();
            var m_text = '';
            if ($(this).parents('div.media-folder').length == 0) {
                m_text = dotclear.msg.confirm_delete_media.replace('%s', m_name);
            } else {
                m_text = dotclear.msg.confirm_delete_directory.replace('%s', m_name);
            }
            if (window.confirm(m_text)) {
                var f = $('#media-remove-hide').get(0);
                f.elements.remove.value = this.href.replace(/^(.*)&remove=(.*?)(&|$)/, '$2');
                this.href = '';
                f.submit();
            }
            return false;
        });
    }
}
);