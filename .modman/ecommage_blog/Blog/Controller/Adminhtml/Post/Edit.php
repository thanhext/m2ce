<?php
namespace Ecommage\Blog\Controller\Adminhtml\Post;
/**
 * Class Edit
 * @package Ecommage\Blog\Controller\Adminhtml\Post
 */
class Edit extends \Ecommage\Blog\Controller\Adminhtml\Post
{

    public function execute()
    {
        $identities = $this->getIdentities();
        $id = $this->getRequest()->getParam($identities);
        $model = $this->_postFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Post no longer exists.'));
                $this->_redirect('*/*/');
                return;
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
        $resultPage->setActiveMenu('Ecommage_Blog::blog_post');
        $resultPage->getConfig()->getTitle()->prepend(__('Post Edit'));
        return $resultPage;
    }

}
