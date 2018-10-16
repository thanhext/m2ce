<?php
namespace NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab;
/**
 * Class Info
 * @package NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */
use NVT\BannerManagement\Model\System\Config\Status;

class Design extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
        $model  = $this->_coreRegistry->registry('item');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $form->setFieldNameSuffix('item');

        $fieldset=$form->addFieldset(
            'item_fieldset',
            ['legend'=>__('Design Item')]
        );
        $fieldset->addField(
            'design',
            'hidden',
            [
                'name' => 'design',
                'label' => __('Style'),
                'title' => __('Style'),
                'required' => false,
                'class' => 'style-url',
                'before_element_html' => $this->getImageHtml('image', $model)
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function getImageHtml($field, $model)
    {
        $js     = '';
        $html   = '';
        $image  = $model->getData($field);
        $style  = $model->getStyle();
        if ($image) {
            $html .= $this->_itemHelper->_init($model)->toHtml();
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
        return $this->_itemHelper->_init($model)->toHtml();
    }


    public function canShowTab()
    {
        return true;
    }

    public function getTabLabel()
    {
        return __('Item Info');
    }

    public function getTabTitle()
    {
        return __('Item Info');
    }

    public function isHidden()
    {
        return false;
    }


}