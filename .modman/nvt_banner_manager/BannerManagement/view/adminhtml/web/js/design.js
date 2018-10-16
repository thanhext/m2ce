define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";
    $.widget('design.item', {
        options: {
            url: 'NVT_BannerManagement/design',
            method: 'post',
            selector: '',
            triggerEvent: 'click'
        },

        _create: function() {
            this._bind();
        },

        _bind: function() {
            var self = this;
            self.element.on(self.options.triggerEvent, '.__icon-add', function() {
                var banner = $(this).closest('.__wrapper_design');
                banner.find('.__content').removeClass('__hide').addClass('__show');
                banner.find('.__content').append('<div class="__sub-item"><input type="hidden" name="sub_item[]" />Click here to change the content.</div>');
                self._move(banner.find('.__sub-item'));
            });
            self.element.on(self.options.triggerEvent, '.__icon-edit', function() {
                console.log('edit');
            });
            self.element.on(self.options.triggerEvent, '.__icon-remove', function() {
                console.log('remove');
            });

        },

        _ajaxSubmit: function() {
            var self = this;
            $.ajax({
                url: self.options.url,
                type: self.options.method,
                dataType: 'json',
                beforeSend: function() {
                    console.log('beforeSend');
                    $('body').trigger('processStart');
                },
                success: function(res) {
                    console.log('success');
                    console.log(res);
                    $('body').trigger('processStop');
                }
            });
        },
        _move: function (element) {

            element.draggable({ containment: ".__wrapper_design",  scroll: false, stop: function() {
                var subItem = $(".__sub-item"),
                    wPosition = subItem.position(),
                    height = subItem.height() - wPosition.top - parseFloat($(this).css("borderTopWidth")) - parseFloat($(this).css("borderBottomWidth")),
                    width = subItem.width() - wPosition.left  - parseFloat($(this).css("borderLeftWidth")) - parseFloat($(this).css("borderRightWidth")),
                    position = $(this).position(),
                    top = (position.top * 100)/height,
                    left = (position.left * 100)/width,
                    w = $(this).width(),
                    h = $(this).height(),
                    style = "z-index: 3; position: absolute; top: "+ top.toFixed(3) +"%;left: "+ left.toFixed(3) +"%; width: "+ w +"px;height: "+ (h + 10) +"px;",
                    color = $(this).css("color"),
                    fontSize = $(this).css("font-size"),
                    text = $(this).text();

                style += "color: "+ color +"; font-size: "+ fontSize +";";

                var data = "{\"text\": \""+ $.trim(text) +"\", \"style\": \""+ style +"\"}";
                $(this).attr('style', style);
                $(this).find("input").val(data);
            }
            });
        },
    });

    return $.design.item;
});
