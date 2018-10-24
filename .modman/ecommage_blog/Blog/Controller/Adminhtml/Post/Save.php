<?php
namespace Ecommage\Blog\Controller\Adminhtml\Post;
/**
 * Class Save
 * @package Ecommage\Blog\Controller\Adminhtml\Post
 */
class Save extends \Ecommage\Blog\Controller\Adminhtml\Post
{
    public function execute()
    {

        $data = $this->getRequest()->getPostValue();
        $identities = $this->getIdentities();
        if ($data) {
            /** @var \Ecommage\Blog\Model\PostFactory $model */
            $model = $this->_postFactory->create();

            $id = $this->getRequest()->getParam($identities);
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                    /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'blog_post_prepare_save',
                ['post' => $model, 'request' => $this->getRequest()]
            );

            try {

                // Save news
                $this->_postRepository->save($model);
                // Display success message
                $this->messageManager->addSuccess(__('The post has been saved.'));
                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', [$identities => $id, '_current' => true]);
                    return;
                }
                // Go to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/index', [$identities => $id]);
        }
    }

}
