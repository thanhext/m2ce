<?php
namespace NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab;
/**
 * Class Properties
 * @package NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab
 */
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
        $model  = $this->_coreRegistry->registry('menu');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('menu_');
        $form->setFieldNameSuffix('menu');
        $id = $model->getMenuId();
        $fieldset = $form->addFieldset(
            'properties_fieldset',
            ['legend'=>__('Properties')]
        );
        //Enable Cache: Yes/No;
        //Menu Effect: Fade/Slide/Toggle
        //Left Menu background color:
        //Responsive: Yes/No;
        //Size to change menu to Mobile version: 767;
        //Left Menu item font size	: 15;
        //Text label: none-Normal/uppercase-Uppercase/lowercase-Lowercase/capitalize-Capitalize;
        //Submenu background color:
        //Submenu main text color:
        //Submenu main link color:
        //Mobile ---------------
        //Mobile Effect: Slide/Blind;
        //Anchor background color:
        //Mobile item font size: 14;
        //Text label: none-Normal/uppercase-Uppercase/lowercase-Lowercase/capitalize-Capitalize;
        //Mobile Item text color:
        //Mobile item background color:
        //Active Mobile item background color:
        //Mobile submenu background color:
        //Mobile Submenu Box title color:
        //Mobile submenu main text color:
        //Mobile submenu main link color:
        //Customize Styles: -----------------
        $fieldset->addField(
            'cache',
            'select',
            [
                'name' => 'cache',
                'options' => $this->getBool(),
                'label' => __('Enable Cache')
            ]
        );
        $fieldset->addField(
            'responsive',
            'select',
            [
                'name' => 'responsive',
                'options' => $this->getBool(),
                'label' => __('Responsive')
            ]
        );
        $fieldset->addField(
            'mobile',
            'text',
            [
                'name'=>'mobile',
                'label'=>__("Size to change menu to Mobile version"),
                'required' => true,
                'class' => 'required-entry',
                'maxlength' =>'255'
            ]
        );
        $data = $model->getData();
        if(!$id){
            $data['cache'] = 1;
            $data['responsive'] = 1;
            $data['mobile'] = 767;
        }
        $form->setValues($data);
        $this->setForm($form);
        parent::_prepareForm();
    }

    protected function getBool(){
        return ['1' => __('Yes'), '0' => __('No')];
    }
   
    /**
     * Return label of tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Properties Menu');
    }

    /**
     * Return title of tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Properties Menu');
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
    public function isHidden()
    {
        return false;
    }

}