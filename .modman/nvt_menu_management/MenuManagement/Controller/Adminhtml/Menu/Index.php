<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Menu;

class Index extends \NVT\MenuManagement\Controller\Adminhtml\Menu
{
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('NVT_MenuManagement::menu_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Menu Manager'));
        return $resultPage;
    }     
}
