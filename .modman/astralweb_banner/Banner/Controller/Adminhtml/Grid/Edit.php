<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Grid;

/**
 * Class Edit
 * @package AstralWeb\Banner\Controller\Adminhtml\Grid
 */
class Edit extends \AstralWeb\Banner\Controller\Adminhtml\Banner
{

    public function execute()
    {
        $indexField = $this->getIndexField();
        $id = $this->getRequest()->getParam($indexField);
        $model = $this->_postFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Banner no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }
        // Restore previously entered form data from session
        $data = $this->_session->getPostData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('current_post', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('AstralWeb_Banner::banner_grid');
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Edit'));
        return $resultPage;
    }

}
