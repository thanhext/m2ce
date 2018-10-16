<?php
namespace NVT\MenuManagement\Block\Adminhtml\Item\Edit;
/**
 * Class Tabs
 * @package NVT\MenuManagement\Block\Adminhtml\Item\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('item_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Item Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'item_general',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock('NVT\MenuManagement\Block\Adminhtml\Item\Edit\Tab\General')->toHtml(),
                'active' => true
            ]
        );
        $this->addTab(
            'item_properties',
            [
                'label' => __('Properties'),
                'title' => __('Properties'),
                'content' => $this->getLayout()->createBlock('NVT\MenuManagement\Block\Adminhtml\Item\Edit\Tab\Properties')->toHtml(),
                'active' => false
            ]
        );

        return parent::_beforeToHtml();
    }
}