<?php
namespace Ecommage\Blog\Controller\Adminhtml\Post;
/**
 * Class Delete
 * @package Ecommage\Blog\Controller\Adminhtml\Post
 */
class Delete extends \Ecommage\Blog\Controller\Adminhtml\Post
{
    public function execute()
    {
        $identities = $this->getIdentities();
        $id = $this->getRequest()->getParam($identities);
        if($id) {
            $model = $this->_postFactory->create();
            $model->load($id);
            if (!$model->getPostId()) {
                $this->messageManager->addError(__('Post is no longer exist'));
            } else {
                try {
                    $model->delete();
                    $this->messageManager->addSuccess(__('Deleted Successfully!'));
                    $this->_redirect('*/*/');
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                    $this->_redirect('*/*/edit', [$identities => $model->getId()]);
                }
            }
        }
    }

}
