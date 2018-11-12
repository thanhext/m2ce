<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Grid;

/**
 * Class MassDelete
 * @package AstralWeb\Banner\Controller\Adminhtml\Grid
 */
class MassDelete extends \AstralWeb\Banner\Controller\Adminhtml\Banner
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
