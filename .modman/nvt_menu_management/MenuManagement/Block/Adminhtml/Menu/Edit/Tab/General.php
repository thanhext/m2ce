<?php
namespace NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab;
/**
 * Class Info
 * @package NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */
use NVT\MenuManagement\Model\System\Config\Status;
use NVT\MenuManagement\Model\System\Config\Type;
class General extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_formFactory;
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }



    protected function _prepareForm()
    {
        //$id = $this->getRequest()->getParam('id');
        $model  = $this->_coreRegistry->registry('menu');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('menu_');
        $form->setFieldNameSuffix('menu');

        $fieldset=$form->addFieldset(
            'menu_fieldset',
            ['legend'=>__('General')]
        );
        $id = $model->getMenuId();
        if($id){
            $fieldset->addField(
                'menu_id',
                'hidden',
                ['name'=>'menu_id']
            );
        }
            $fieldset->addField(
              'title',
              'text',
              [
                  'name'=>'title',
                  'label'=>__('Title'),
                  'required' => true,
                  'class' => 'required-entry',
                  'maxlength' =>'255'
              ]
            );
        $fieldset->addField(
            'type',
            'select',
            [
                'name'=>'type',
                'label'=>__('Type'),
                'options'=>[ Type::TYPE_HORIZONTAL => __('Horizontal'), Type::TYPE_VERTICAL => __('Vertical') ]
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
            'is_active',
            'select',
            [
                'name'=>'is_active',
                'label'=>__('Status'),
                'options'=>[ Status::STATUS_DISABLE => __('Disable'), Status::STATUS_ENABLED => __('Enable')],
                'value' => ['Enable'=> Status::STATUS_ENABLED]
            ]
        );
        $data = $model->getData();
        if(!$id){
            $data['is_active'] = Status::STATUS_ENABLED;
        }
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
        return __('Menu Info');
    }

    public function getTabTitle()
    {
        return __('Menu Info');
    }

    public function isHidden()
    {
        return false;
    }


}