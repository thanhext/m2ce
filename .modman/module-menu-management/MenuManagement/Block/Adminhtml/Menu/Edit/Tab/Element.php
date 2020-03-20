<?php
namespace NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab;
/**
 * Class Element
 * @package NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab
 */
class Element extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
        $model  = $this->_coreRegistry->registry('menu');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('menu_');
        $form->setFieldNameSuffix('menu');
        $id = $model->getMenuId();
        $fieldset = $form->addFieldset(
            'element_fieldset',
            ['legend'=>__('Element')]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        parent::_prepareForm();
    }


    /**
     * Return label of tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Menu Element');
    }

    /**
     * Return title of tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Menu Element');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isHidden() {
        return false;
    }
    public function getMenu() {
        return $this->_coreRegistry->registry('menu');
    }

}