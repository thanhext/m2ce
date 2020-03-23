<?php

namespace T2N\BannerManager\Controller\Adminhtml\Banner;

use Exception;
use T2N\BannerManager\Controller\Adminhtml\Banner;

/**
 * Class Delete
 */
class Delete extends Banner
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model = $this->_bannerFactory->create();
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('Banner is no longer exist'));
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
