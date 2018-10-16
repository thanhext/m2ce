<?php
namespace NVT\MenuManagement\Block\Adminhtml\Item\Edit\Tab;
/**
 * Class Info
 * @package NVT\MenuManagement\Block\Adminhtml\Item\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */
use NVT\MenuManagement\Model\System\Config\Status;
class Properties extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_formFactory;
    protected $_coreRegistry;
    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
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
            ['legend'=>__('General')]
        );
        $fieldset->addField(
            'style',
            'hidden',
            [
                'name' => 'style',
                'label' => __('Style'),
                'title' => __('Style'),
                'required' => false,
                'class' => 'style-url'
            ]
        );
        $fieldset->addField(
            'class',
            'text',
            [
                'name' => 'class',
                'label' => __('Prefix Class'),
                'title' => __('Prefix Class'),
                'required' => false
            ]
        );
        $fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'class' => 'validate-zero-or-greater',
                'required' => false,
                'note'      => '(*.jpg, *.png, *.gif)'
            ]
        );
        $fieldset->addField(
            'short_description',
            'textarea',
            [
                'name'=>'short_description',
                'label'=>__('Short Description'),
                'maxlength' =>'255',
                'note' => 'Limited characters is 255'
            ]
        );
        $fieldset->addField(
            'description',
            'editor',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => false,
                'style' => 'height:10em',
                'wysiwyg'   => true,
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
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