define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function($, modal) {
    'use strict';
    $.widget('widget.applyBannerItem', {
        options: {
            url: null,
            url_reload: null,
            omd: {type: "slide",responsive: true,innerScroll: true,buttons: false}
        },
        _create: function() {
            this._bind();
        },
        _bind: function() {
            var self = this,
                config = self.options;

            self.element.on('click', function() {
                self._send()
            });
        },
        _send: function (data) {
            var popup,
                self = this,
                config = self.options;

            $.ajax({
                url: config.url,
                type: 'post',
                data:{isAjax: 1, form_key: window.FORM_KEY, data:data},
                beforeSend: function () {
                    // $('body').trigger('processStart');
                },
                success: function (res) {
                    console.log(res);
                    $('.form-apply-item').html(res.html);
                    popup  = $('.form-apply-item');
                    config.omd.buttons = [
                        {
                            text: $.mage.__('Save Item'),
                            class: 'action-apply-item scalable save primary',
                            click: function () {
                                var form = $(this.element).closest('.modal-inner-wrap').find('form');
                                self._save(form);
                                this.closeModal();
                            }
                        },
                        {
                            text: $.mage.__('Back'),
                            class: 'action-back scalable back',
                            click: function () {
                                this.closeModal();
                            }
                        }
                    ];
                    modal(config.omd, popup);
                    popup.modal('openModal');
                    // $('body').trigger('processStop');
                }
            });

        },
        _save: function (form) {
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(data)
                {
                    console.log(data); // show response from the php script.
                }
            });
        }

    });
    return $.widget.applyBannerItem;
});