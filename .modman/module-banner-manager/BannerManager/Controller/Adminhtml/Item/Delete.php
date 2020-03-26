<?php

namespace T2N\BannerManager\Controller\Adminhtml\Item;

use Exception;

/**
 * Class Delete
 */
class Delete extends BannerItem
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model = $this->_itemFactory->create();
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('Banner Item is no longer exist'));
            } else {
                try {
                    $model->delete();
                    $this->messageManager->addSuccess(__('Deleted Successfully!'));
                    $this->_redirect('*/*/');
                } catch (Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                }
            }
        }
    }
}
