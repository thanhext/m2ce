<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Item;

class Index extends \NVT\BannerManagement\Controller\Adminhtml\Item
{
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('NVT_BannerManagement::item_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Item Manager'));
        return $resultPage;
    }     
}
