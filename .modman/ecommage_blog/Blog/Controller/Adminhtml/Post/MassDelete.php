<?php
namespace Ecommage\Blog\Controller\Adminhtml\Post;
/**
 * Class MassDelete
 * @package Ecommage\Blog\Controller\Adminhtml\Post
 */
class MassDelete extends \Ecommage\Blog\Controller\Adminhtml\Post
{
    public function execute()
    {
        $ids = $this->getRequest()->getParam('selected');
        $model = $this->_postFactory->create();
        foreach ($ids as $id) {
            try {
                $model->load($id)->delete();
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        if (count($ids)) {
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) were deleted.', count($ids))
            );
        }
        $this->_redirect('*/*/index');
    }
}
