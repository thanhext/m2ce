<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Grid;

/**
 * Class MassStatus
 * @package AstralWeb\Banner\Controller\Adminhtml\Grid
 */
class MassStatus extends \AstralWeb\Banner\Controller\Adminhtml\Banner
{


    public function execute()
    {
        $ids        = $this->getRequest()->getParam('selected');
        $status     = $this->getRequest()->getParam('status');
        $indexField = $this->getIndexField();
        if(is_array($ids)) {
            foreach ($ids as $id) {
                try {
                    $model = $this->_postFactory->create();
                    $model->setData(array($indexField => $id, 'is_active' => $status));
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
