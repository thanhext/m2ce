<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Banner;

class Index extends \NVT\BannerManagement\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('NVT_BannerManagement::banner_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Manager'));
        return $resultPage;
    }     
}
