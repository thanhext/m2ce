<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Item;
/**
 * Class Edit
 * @package NVT\MenuManagement\Controller\Adminhtml\Item
 */
class Edit extends \NVT\MenuManagement\Controller\Adminhtml\Item
{

    public function execute()
    {
        $identities = \NVT\MenuManagement\Model\Item::CACHE_TAG . '_id';
        $id = $this->getRequest()->getParam($identities);
        $model = $this->_itemFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getItemId()) {
                $this->messageManager->addError(__('This Item no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // Restore previously entered form data from session
        $data = $this->_session->getItemData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('item', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('NVT_MenuManagement::item_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Item Edit'));
        return $resultPage;
    }

}
