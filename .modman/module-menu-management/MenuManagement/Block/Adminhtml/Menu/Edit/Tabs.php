<?php
namespace NVT\MenuManagement\Block\Adminhtml\Menu\Edit;
/**
 * Class Tabs
 * @package NVT\MenuManagement\Block\Adminhtml\Menu\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('menu_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Menu Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'menu_general',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock('NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab\General')->toHtml(),
                'active' => true
            ]
        );
        $this->addTab(
            'menu_properties',
            [
                'label' => __('Properties'),
                'title' => __('Properties'),
                'content' => $this->getLayout()->createBlock('NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab\Properties')->toHtml(),
                'active' => false
            ]
        );
        $this->addTab(
            'menu_element',
            [
                'label' => __('Element'),
                'title' => __('Element'),
                'content' => $this->getLayout()
                                    ->createBlock('NVT\MenuManagement\Block\Adminhtml\Menu\Edit\Tab\Element')
                                    ->setTemplate('NVT_MenuManagement::items.phtml')
                                    ->toHtml(),
                'active' => false
            ]
        );
        return parent::_beforeToHtml();
    }
}