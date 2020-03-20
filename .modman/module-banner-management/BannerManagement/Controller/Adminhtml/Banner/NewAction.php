<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Banner;
/**
 * Class NewAction
 * @package NVT\BannerManagement\Controller\Adminhtml\Banner
 */
class NewAction extends \NVT\BannerManagement\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $this->_forward('edit');
    }
}