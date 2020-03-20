<?php
namespace NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab;
/**
 * Class Info
 * @package NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab
 * thomas check $_coreRegistry, $_formFactory
 */
use NVT\BannerManagement\Model\System\Config;

class Info extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var
     */
    protected $_formFactory;
    /**
     * @var
     */
    protected $_coreRegistry;
    /**
     * @var \NVT\BannerManagement\Model\System\Config\Type
     */
    protected $_type;
    /**
     * @var Status
     */
    protected $_status;
    /**
     * Info constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \NVT\BannerManagement\Model\System\Config\Type $type
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Config\Type $type,
        Config\Status $status,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_type = $type;
        $this->_status = $status;
    }



    protected function _prepareForm()
    {
        $model  = $this->_coreRegistry->registry('banner');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('banner_');
        $form->setFieldNameSuffix('banner');

        $fieldset=$form->addFieldset(
            'banner_fieldset',
            ['legend'=>__('General')]
        );
        $id = $model->getBannerId();
        if($id){
            $fieldset->addField(
                'banner_id',
                'hidden',
                ['name'=>'banner_id']
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
            'type',
            'select',
            [
                'name'      => 'type',
                'label'     => __('Type'),
                'options'   => $this->_type->toArray(),
                'value'     => Config\Type::TYPE_SLIDER
            ]
        );
        $fieldset->addField(
            'is_active',
            'select',
            [
                'name'      => 'is_active',
                'label'     => __('Status'),
                'options'   => $this->_status->toArray(),
                'value'     => Config\Status::STATUS_ENABLED
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
        return __('Banner Info');
    }

    public function getTabTitle()
    {
        return __('Banner Info');
    }

    public function isHidden()
    {
        return false;
    }


}