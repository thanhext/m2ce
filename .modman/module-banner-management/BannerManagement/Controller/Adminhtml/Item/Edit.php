<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Item;
/**
 * Class Edit
 * @package NVT\BannerManagement\Controller\Adminhtml\Item
 */
class Edit extends \NVT\BannerManagement\Controller\Adminhtml\Item
{

    public function execute()
    {
        $identities = \NVT\BannerManagement\Model\Item::CACHE_TAG . '_id';
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
        $resultPage->setActiveMenu('NVT_BannerManagement::item_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Item Edit'));
        return $resultPage;
    }

}
