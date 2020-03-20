<?php
namespace NVT\MenuManagement\Block\Adminhtml\Item\Edit\Tab;
/**
 * Class Info
 * @package NVT\MenuManagement\Block\Adminhtml\Item\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */
use NVT\MenuManagement\Model\System\Config\Status;
class General extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $_formFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @var \NVT\MenuManagement\Model\System\Config\ItemMenu
     */
    protected $_itemMenu;
    /**
     * @var \NVT\MenuManagement\Model\System\Config\ItemMenu
     */
    protected $_itemType;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \NVT\MenuManagement\Model\System\Config\ItemMenu $itemMenu,
        \NVT\MenuManagement\Model\System\Config\ItemType $itemType,
        array $data = []
    ) {
        $this->_itemType = $itemType;
        $this->_itemMenu = $itemMenu;
        $this->_systemStore = $systemStore;
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
        $id = $model->getItemId();
        if($id){
            $fieldset->addField(
                'item_id',
                'hidden',
                ['name'=>'item_id']
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
        $fieldset->addType(
            'mycustomfield',
            '\NVT\MenuManagement\Block\Adminhtml\Customformfield\CustomRenderer'
        );
        $fieldset->addField(
            'type',
            'mycustomfield',
            [
                'name'=>'type',
                'label'=>__('Type'),
                'title'=>__('Type')
            ]
        );
        $fieldset->addField(
            'link',
            'text',
            [
                'name'=>'link',
                'label'=>__('Link'),
                'required' => true,
                'class' => 'validate-url',
                'maxlength' =>'255'
            ]
        );
        $fieldset->addField(
            'menu_id',
            'select',
            [
                'name'=>'menu_id',
                'label'=>__('Menu'),
                'required' => true,
                'values' => $this->_itemMenu->getAllOptions()
            ]
        )->setSize(2);

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
        return __('Item General');
    }

    public function getTabTitle()
    {
        return __('Item General');
    }

    public function isHidden()
    {
        return false;
    }


}