<?php
namespace NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab;
/**
 * Class Info
 * @package NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */
use NVT\BannerManagement\Model\System\Config\Status;
class Info extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
     * @var \NVT\BannerManagement\Model\System\Config\ItemBanner
     */
    protected $_itemBanner;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \NVT\BannerManagement\Model\System\Config\ItemBanner $itemBanner,
        array $data = []
    ) {
        $this->_itemBanner = $itemBanner;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }



    protected function _prepareForm()
    {
        //$id = $this->getRequest()->getParam('id');
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

        $fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'class' => 'required-entry',
                'required' => true,
                'note'      => '(*.jpg, *.png, *.gif)'
            ]
        );
        
        $fieldset->addField(
            'banner_id',
            'multiselect',
            [
                'name'=>'banner_id',
                'label'=>__('Banner'),
                'required' => true,
                'size' => 4,
                'value' => [2],
                'values' => $this->_itemBanner->getAllOptions()
            ]
        )->setSize(2);
//        /**
//         * Check is single store mode
//         */
//        if (!$this->_storeManager->isSingleStoreMode()) {
//            $field = $fieldset->addField(
//                'store_id',
//                'multiselect',
//                [
//                    'name' => 'store_id',
//                    'label' => __('Store View'),
//                    'title' => __('Store View'),
//                    'required' => true,
//                    'value' => 0,
//                    'values' => $this->_systemStore->getStoreValuesForForm(false, true),
//                    'disabled' => false
//                ]
//            );
//            $renderer = $this->getLayout()->createBlock(
//                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
//            );
//            $field->setRenderer($renderer);
//        } else {
//            $fieldset->addField(
//                'store_id',
//                'hidden',
//                ['name' => 'store_id', 'value' => $this->_storeManager->getStore(true)->getId()]
//            );
//            $model->setStoreId($this->_storeManager->getStore(true)->getId());
//        }


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
//        var_dump($data); die;
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