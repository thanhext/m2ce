define(
    [
        'underscore',
        'ko',
        'jquery',
        'Magento_Ui/js/lib/view/utils/async',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/quote',
        'Amasty_Checkout/js/model/payment/payment-loading',
        'Amasty_Checkout/js/model/cart/totals-processor/default',
        'Amasty_Checkout/js/model/shipping-registry',
        'Amasty_Checkout/js/model/address-form-state',
        'Amasty_Checkout/js/model/events',
        'uiRegistry',
        'rjsResolver'
    ],
    function (
        _,
        ko,
        $,
        async,
        storage,
        checkoutData,
        shippingService,
        selectShippingMethod,
        setShippingInformationAction,
        quote,
        paymentLoader,
        totalsProcessor,
        shippingRegistry,
        addressFormState,
        events,
        registry,
        onLoad
    ) {
        'use strict';

        var instance = null;

        function removeAmazonPayButton() {
            var amazonPaymentButton = $('#PayWithAmazon_amazon-pay-button img');

            if (amazonPaymentButton.length > 1) {
                amazonPaymentButton.not(':first').remove();
            }
        }

        return function (Shipping) {
            return Shipping.extend({
                allowedDynamicalSave: false,
                allowedDynamicalValidation: true,
                isUpdateCancelledByBilling: false,
                isInitialDataSaved: false,
                previousShippingMethodData: {},
                initialize: function () {
                    this._super();
                    instance = this;
                    onLoad(shippingRegistry.initObservers.bind(shippingRegistry, this.elems));

                    if (!this.isFormInline) {
                        shippingRegistry.excludedCollectionNames.push(
                            'shipping-address-fieldset',
                            'additional-fieldsets'
                        );
                    }

                    shippingRegistry.isAddressChanged.subscribe(this.additionalFieldsObserver.bind(this));
                    quote.shippingAddress.subscribe(this.shippingAddressObserver.bind(this));
                    quote.shippingMethod.subscribe(this.storeOldMethod, this, "beforeChange");
                    quote.shippingMethod.subscribe(this.shippingMethodObserver.bind(this));
                    this.allowedDynamicalSave = true;

                    registry.get('checkout.steps.shipping-step.shippingAddress.before-form.amazon-widget-address.before-widget-address.amazon-checkout-revert',
                        function (component) {
                            component.isAmazonAccountLoggedIn.subscribe(function (loggedIn) {
                                if (!loggedIn) {
                                    registry.get('checkout.steps.shipping-step.shippingAddress', function (component) {
                                        if (component.isSelected()) {
                                            component.selectShippingMethod(quote.shippingMethod());
                                        }
                                    });
                                }
                            });
                        }
                    );

                    registry.get('checkout.steps.billing-step.payment.payments-list.amazon_payment', function (component) {
                        if (component.isAmazonAccountLoggedIn()) {
                            $('button.action-show-popup').hide();
                        }
                    });

                    registry.get('checkout.steps.shipping-step.shippingAddress.customer-email.amazon-button-region.amazon-button',
                        function (component) {
                            async.async({
                                selector: "#PayWithAmazon_amazon-pay-button img"
                            }, function () {
                                removeAmazonPayButton();
                            });

                            component.isAmazonAccountLoggedIn.subscribe(function (loggedIn) {
                                if (!loggedIn) {
                                    removeAmazonPayButton();
                                }
                            });
                        }
                    );

                    quote.billingAddress.subscribe(function () {
                        if (this.isUpdateCancelledByBilling) {
                            this.validateAndSaveIfChanged();
                        }
                    }, this);

                    events.onBeforeShippingSave(shippingRegistry.register.bind(shippingRegistry));
                    events.onBeforeShippingSave(paymentLoader.bind(null, true));
                    events.onAfterShippingSave(paymentLoader.bind(null, false));
                },

                /**
                 * If checkout already have all shipping information
                 * then execute validate and save process because we dont have any triggers
                 * and save should be executed on storefront for 3rd party extensions compatibility
                 */
                saveInitialData: function () {
                    if (!this.isInitialDataSaved) {
                        onLoad(function () {
                            if (this.silentValidation()) {
                                this.validateAndSaveIfChanged();
                            }
                        }.bind(this));

                        this.isInitialDataSaved = true;
                    }
                },

                /**
                 * Validate shipping without showing any errors
                 *
                 * @return {Boolean}
                 */
                silentValidation: function () {
                    var invalidElement,
                        result = !_.isEmpty(quote.shippingMethod()) && !_.isEmpty(quote.shippingAddress());

                    if (result && this.isFormInline) {
                        invalidElement = _.find(shippingRegistry.addressComponents ,function (module) {
                            return ko.isObservable(module.required)
                                && ko.isObservable(module.value)
                                && ko.isObservable(module.visible)
                                && ko.isObservable(module.disabled)
                                && module.required.peek()
                                && module.visible.peek()
                                && !module.disabled.peek()
                                && _.isEmpty(module.value.peek());
                        });

                        result = invalidElement === void 0;
                    }

                    return result;
                },

                setShippingInformation: function () {
                    var result;

                    this.allowedDynamicalSave = false;
                    result = this._super();
                    this.allowedDynamicalSave = true;

                    return result;
                },

                selectShippingMethod: function (method) {
                    window.loaderIsNotAllowed = true;
                    this._super(method);
                    instance.shippingAddressObserver();
                    delete window.loaderIsNotAllowed;

                    return true;
                },

                validateShippingInformation: function () {
                    var result;

                    this.allowedDynamicalValidation = false;
                    result = this._super();
                    this.allowedDynamicalValidation = true;

                    return result;
                },

                /**
                 * Store before change shipping method, cause sometimes shipping methods updates always (not by change)
                 *
                 * @param {Object} oldMethod
                 */
                storeOldMethod: function (oldMethod) {
                    this.previousShippingMethodData = oldMethod;
                },

                /**
                 * Calculate Totals for changed shipping method.
                 * Necessary only if dynamical shipping save is not working (i.e. shipping is not valid)
                 *
                 * @param {Object} method
                 */
                shippingMethodObserver: function (method) {
                    if (method && (this.isFormInline || this.validateShippingInformation())
                        && !shippingRegistry.savedAddress && !_.isEqual(this.previousShippingMethodData, method)
                    ) {
                        totalsProcessor(quote.shippingAddress());
                    }
                },

                /**
                 * Trigger shipping address validation and save on additional address fields change
                 * @param {Boolean} isChanged
                 */
                additionalFieldsObserver: function (isChanged) {
                    if (isChanged && !shippingRegistry.isEstimationHaveError()) {
                        this.shippingAddressObserver();
                    } else {
                        paymentLoader(false);
                    }
                },

                /**
                 * Save Shipping data dynamically
                 */
                shippingAddressObserver: function () {
                    if (!this.allowedDynamicalSave || this.isFormInline && $('#checkout-loader:visible').length) {
                        return;
                    }
                    if (shippingService.isLoading()) {
                        // avoid error "shipping method not available"
                        // eslint-disable-next-line vars-on-top
                        var saveSubscription = shippingService.isLoading.subscribe(function (isLoading) {
                            if (!isLoading) {
                                saveSubscription.dispose();
                                this.validateAndSaveIfChanged();
                            }
                        }.bind(this));
                    } else {
                        if (saveSubscription) {
                            saveSubscription.dispose();
                        }
                        this.validateAndSaveIfChanged();
                    }
                },

                validateAndSaveIfChanged: function () {
                    if (!this.allowedDynamicalSave
                        || this.isBillingAddressFormVisible()
                        || !shippingRegistry.isHaveUnsavedShipping()
                    ) {
                        paymentLoader(false);
                        return;
                    }
                    var isShippingValid = !this.allowedDynamicalValidation;
                    // allowedDynamicalValidation - for avoid circular dependency
                    if (this.allowedDynamicalValidation) {
                        this.allowedDynamicalSave = false;
                        isShippingValid = this.validateShippingInformation();
                        this.allowedDynamicalSave = true;
                    }

                    /*
                     if isFormInline = true, method validateShippingInformation
                     will set shipping address and this observer will be executed.
                     validateShippingInformation - also validate email, which is not part of Shipping information.
                    */
                    if (isShippingValid || (this.isFormInline && !this.source.get('params.invalid'))) {
                        window.loaderIsNotAllowed = true;
                        setShippingInformationAction();
                        delete window.loaderIsNotAllowed;
                    } else {
                        paymentLoader(false);
                    }
                },

                getNameShippingAddress: function () {
                    return window.checkoutConfig.quoteData.block_info.block_shipping_address['value'];
                },

                getNameShippingMethod: function () {
                    return window.checkoutConfig.quoteData.block_info.block_shipping_method['value'];
                },

                isPostNlEnable: function () {
                    return window.checkoutConfig.quoteData.posnt_nl_enable;
                },

                /**
                 * Trigger Shipping data Validate Event.
                 */
                triggerShippingDataValidateEvent: function () {
                    this.source.trigger('shippingAddress.data.validate');

                    if (this.source.get('shippingAddress.custom_attributes')) {
                        this.source.trigger('shippingAddress.custom_attributes.data.validate');
                    }
                },

                validatePlaceOrder: function () {
                    var loginFormSelector = 'form[data-role=email-with-possible-login]',
                        emailValidationResult = this.isCustomerLoggedIn();

                    if (!emailValidationResult) {
                        $(loginFormSelector).validation();
                        emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
                    }

                    if (!emailValidationResult) {
                        $(loginFormSelector + ' input[name=username]').focus();

                        return false;
                    }

                    if (this.isFormInline) {
                        this.source.set('params.invalid', false);
                        this.triggerShippingDataValidateEvent();

                        if (
                            this.source.get('params.invalid')
                        ) {
                            return false;
                        }
                    }

                    return true;
                },

                isModernDesign: function () {
                    return window.checkoutDesign === 'modern';
                },

                /**
                 * @param {Object} method - shipping method
                 * @return {string}
                 */
                getMethodTooltipText: function (method) {
                    var comment = '';

                    if (this.isModernDesign() && method.error_message) {
                        comment = method.error_message;
                    }

                    if (!comment) {
                        comment = this.getCommentShippingMethod(method);
                    }

                    return comment;
                },

                /**
                 * Compatibility with Amasty_ShippingTableRates and Amasty_StorePickup
                 *
                 * @param {Object} method - shipping method
                 * @returns {string}
                 */
                getCommentShippingMethod: function (method) {
                    if (!method) {
                        return '';
                    }

                    if (method.comment && typeof method.comment === 'string') {
                        return method.comment;
                    }

                    if (method.extension_attributes) {
                        if (method.extension_attributes.amstorepick_comment) {
                            return method.extension_attributes.amstorepick_comment;
                        }

                        if (method.extension_attributes.amstartes_comment) {
                            return method.extension_attributes.amstartes_comment;
                        }
                    }

                    return '';
                },

                getAdditionalClassForIcons: function (method) {
                    if (this.isModernDesign()
                        && method.hasOwnProperty('error_message')
                        && method.error_message
                    ) {
                        return '-error';
                    }

                    return '';
                },

                isShippingMethodTooltip: function (method) {
                    return this.isModernDesign() && this.getMethodTooltipText(method);
                },

                getColspanCarrier: function (method) {
                    if (this.isShippingMethodTooltip(method)) {
                        return 1;
                    }

                    return 2;
                },

                /**
                 * disable focus on "silent" validation
                 * "silent" validation can be triggered by payment additional validator
                 */
                focusInvalid: function () {
                    if (!window.silentShippingValidation) {
                        this._super();
                    }

                    return this;
                },

                /**
                 * check on visible billing address form
                 * @returns {boolean}
                 */
                isBillingAddressFormVisible: function () {
                    this.isUpdateCancelledByBilling = !addressFormState.isBillingSameAsShipping()
                        && addressFormState.isBillingFormVisible();

                    return this.isUpdateCancelledByBilling;
                }
            });
        };
    }
);
