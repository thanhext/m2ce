<?php
namespace NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab;
/**
 * Class Properties
 * @package NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab
 */
class Properties extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_formFactory;
    protected $_coreRegistry;
    protected $_itemHelper;
    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \NVT\BannerManagement\Helper\Item $itemHelper,
        array $data = []
    ) {
        $this->_itemHelper = $itemHelper;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    protected function _prepareForm()
    {
        $model  = $this->_coreRegistry->registry('banner');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('banner_');
        $form->setFieldNameSuffix('banner');

        $fieldset = $form->addFieldset(
            'properties_fieldset',
            ['legend'=>__('Initialize Slideshow')]
        );
        $fieldset->addType(
            'mycustomfield',
            '\NVT\BannerManagement\Block\Adminhtml\Customformfield\CustomRenderer'
        );
        $fieldset->addField(
            'properties',
            'mycustomfield',
            [
                'name' => 'properties',
                'label' => __('Properties'),
                'title' => __('Properties'),
                'nfield' => 4,
                'required' => false,
                'class' => ''
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        parent::_prepareForm();
    }
    protected function getPropertiesHtml($field, $model)
    {
        $js     = '';
        $html   = '';
        $data  = $model->getData($field);
        if ($field) {
            $html .= '<div class="group-properties">';
            $html .= '<label class="label admin__field-label" for="banner_pagination" data-ui-id="adminhtml-banner-edit-tab-info-0-fieldset-element-text-banner-pagination-label"><span>Pagination</span></label>';
            $html .= '    <div class="admin__field-control control">';
            $html .= '        <input id="banner_pagination" name="banner[pagination]" data-ui-id="adminhtml-banner-edit-tab-info-0-fieldset-element-text-banner-pagination" value="" class="required-entry input-text admin__control-text required-entry _required" maxlength="255" type="text" aria-required="true">';
            $html .= '    </div>';
            $html .= '</div>';
            $js .= '<script>require(["jquery", "jquery/ui", "domReady!"], function($){';

            $js .= '$("body").on("change", "#item_description", function(){';
            $js .= '    $(".item-wrapper .caption").html($(this).val());';
            $js .= '});';

            $js .= '$(".item-wrapper .caption").draggable({ containment: ".item-wrapper", scroll: false, stop: function() {
                            var element = $(".item-wrapper");
                            var wPosition = element.position();
                            var height = element.height() - wPosition.top - parseFloat($(this).css("borderTopWidth")) - parseFloat($(this).css("borderBottomWidth"));
                            var width = element.width() - wPosition.left  - parseFloat($(this).css("borderLeftWidth")) - parseFloat($(this).css("borderRightWidth"));
                            var position = $(this).position();
                            var top = (position.top * 100)/height;
                            var left = (position.left * 100)/width;
                            $("#item_style").val("top: "+ top.toFixed(3) +"%;left: "+ left.toFixed(3) +"%;");
                          } 
                        });';

            $js .= '});</script>';
            $html .= $js;
        }
        return $html;
    }
    /**
     * Return label of tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Properties Banner');
    }

    /**
     * Return title of tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Properties Banner');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

}