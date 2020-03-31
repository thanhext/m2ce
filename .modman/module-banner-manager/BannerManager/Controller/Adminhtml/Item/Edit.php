<?php

namespace T2N\BannerManager\Controller\Adminhtml\Item;

use Magento\Backend\Model\View\Result\Page;

/**
 * Class Edit
 */
class Edit extends BannerItem
{
    public function execute()
    {
        $id    = $this->getRequest()->getParam('entity_id');
        var_dump($id); die;
        $model = $this->_itemFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Banner Item no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // Restore previously entered form data from session
        $data = $this->_session->getBannerData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('banner_item', $model);

        /** @var Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Item Edit'));
        return $resultPage;
    }
}
