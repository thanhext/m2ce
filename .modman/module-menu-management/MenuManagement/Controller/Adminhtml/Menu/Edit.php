<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Menu;
/**
 * Class Edit
 * @package NVT\MenuManagement\Controller\Adminhtml\Menu
 */
class Edit extends \NVT\MenuManagement\Controller\Adminhtml\Menu
{

    public function execute()
    {
        $identities = \NVT\MenuManagement\Model\Menu::CACHE_TAG . '_id';
        $id = $this->getRequest()->getParam($identities);
        $model = $this->_menuFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getMenuId()) {
                $this->messageManager->addError(__('This Menu no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // Restore previously entered form data from session
        $data = $this->_session->getMenuData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('menu', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('NVT_MenuManagement::menu_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Menu Edit'));
        return $resultPage;
    }

}
