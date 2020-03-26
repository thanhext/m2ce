<?php

namespace T2N\BannerManager\Controller\Adminhtml\Index;

use Magento\Backend\Model\View\Result\Page;

/**
 * Class Edit
 */
class Edit extends Banner
{
    public function execute()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = $this->_bannerFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Banner no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // Restore previously entered form data from session
        $data = $this->_session->getBannerData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('banner', $model);

        /** @var Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Edit'));
        return $resultPage;
    }
}
