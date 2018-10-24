<?php
namespace Ecommage\Blog\Controller\Adminhtml\Post;
/**
 * Class MassStatus
 * @package Ecommage\Blog\Controller\Adminhtml\Post
 */
class MassStatus extends \Ecommage\Blog\Controller\Adminhtml\Post
{


    public function execute()
    {
        $ids        = $this->getRequest()->getParam('selected');
        $identities = $this->getIdentities();
        $status     = $this->getRequest()->getParam('status');
        if(is_array($ids)) {
            foreach ($ids as $id) {
                try {
                    $model = $this->_postFactory->create();
                    $model->setData(array($identities => $id, 'is_active' => $status));
                    $model->save();
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        }
        if (count($ids)) {
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) were change status.', count($ids))
            );
        }
        $this->_redirect('*/*/index');
    }
}
