/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'Magento_Ui/js/form/element/textarea'
], function (Element) {
    'use strict';

    return Element.extend({
        defaults: {
            cols: 15,
            rows: 4,
            elementTmpl: 'AstralWeb_Banner/form/element/textarea'
        },
        initialize: function () {
            this._super();
            return this;
        },
        addLayoutUpdate: function () {
            console.log(this);
            console.log(this.default);
        }
    });
});
