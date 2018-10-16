<?php
namespace NVT\BannerManagement\Block\Adminhtml\Item\Edit;
/**
 * Class Tabs
 * @package NVT\BannerManagement\Block\Adminhtml\Item\Edit
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
            'item_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock('NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab\Info')->toHtml(),
                'active' => true
            ]
        );
//        $this->addTab(
//            'item_image',
//            [
//                'label' => __('Image Properties'),
//                'title' => __('Image Properties'),
//                'content' => $this->getLayout()->createBlock('NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab\Image')->toHtml(),
//                'active' => false
//            ]
//        );
//        $this->addTab(
//            'item_design',
//            [
//                'label' => __('Design Item'),
//                'title' => __('Design Item'),
//                'content' => $this->getLayout()->createBlock('NVT\BannerManagement\Block\Adminhtml\Item\Edit\Tab\Design')->toHtml(),
//                'active' => false
//            ]
//        );
        return parent::_beforeToHtml();
    }
}