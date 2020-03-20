<?php
namespace NVT\BannerManagement\Block\Adminhtml\Banner\Edit;
/**
 * Class Tabs
 * @package NVT\BannerManagement\Block\Adminhtml\Banner\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('banner_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Banner Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'banner_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock('NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab\Info')->toHtml(),
                'active' => true
            ]
        );
        $this->addTab(
            'banner_properties',
            [
                'label' => __('Properties'),
                'title' => __('Properties'),
                'content' => $this->getLayout()->createBlock('NVT\BannerManagement\Block\Adminhtml\Banner\Edit\Tab\Properties')->toHtml(),
                'active' => false
            ]
        );
        return parent::_beforeToHtml();
    }
}