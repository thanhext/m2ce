<?php

namespace T2N\BannerManager\Controller\Adminhtml\Index;

use Magento\Backend\Model\View\Result\Page;

/**
 * Class Edit
 */
class Edit extends Banner
{
    /**
     * @return Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
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

        $this->dataPersistor->set('banner_entity', $model->getData());
        /** @var Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Edit'));
        return $resultPage;
    }
}
