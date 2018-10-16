<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Item;

class Index extends \NVT\MenuManagement\Controller\Adminhtml\Item
{
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('NVT_MenuManagement::item_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Item Manager'));
        return $resultPage;
    }     
}
