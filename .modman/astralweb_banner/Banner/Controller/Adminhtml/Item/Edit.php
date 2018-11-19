<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Item;

/**
 * Class Edit
 * @package AstralWeb\Banner\Controller\Adminhtml\Item
 */
class Edit extends \AstralWeb\Banner\Controller\Adminhtml\Item
{

    public function execute()
    {
        $indexField = $this->getIndexField();
        $id = $this->getRequest()->getParam($indexField);
        $model = $this->_postFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Item no longer exists.'));
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
        $resultPage->setActiveMenu('AstralWeb_Banner::item_grid');
        $resultPage->getConfig()->getTitle()->prepend(__('Item Edit'));
        return $resultPage;
    }

}
