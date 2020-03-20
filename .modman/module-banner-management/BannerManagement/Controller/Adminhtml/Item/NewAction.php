<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Item;
/**
 * Class NewAction
 * @package NVT\BannerManagement\Controller\Adminhtml\Item
 */
class NewAction extends \NVT\BannerManagement\Controller\Adminhtml\Item
{
    public function execute()
    {
        $this->_forward('edit');
    }
}