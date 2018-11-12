<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Grid;

/**
 * Class Delete
 * @package AstralWeb\Banner\Controller\Adminhtml\Grid
 */
class Delete extends \AstralWeb\Banner\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $indexField = $this->getIndexField();
        $id = $this->getRequest()->getParam($indexField);
        if($id) {
            $model = $this->_postFactory->create();
            $model->load($id);
            if (!$model->getPostId()) {
                $this->messageManager->addError(__('Banner is no longer exist'));
            } else {
                try {
                    $model->delete();
                    $this->messageManager->addSuccess(__('Deleted Successfully!'));
                    $this->_redirect('*/*/');
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                    $this->_redirect('*/*/edit', [$indexField => $model->getId()]);
                }
            }
        }
    }

}
