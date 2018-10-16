<?php

namespace NVT\BannerManagement\Controller\Adminhtml\Banner;
/**
 * Class Delete
 * @package NVT\BannerManagement\Controller\Adminhtml\Banner
 */
class Delete extends \NVT\BannerManagement\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $identities = \NVT\BannerManagement\Model\Banner::CACHE_TAG . '_id';
        $id = $this->getRequest()->getParam($identities);
        if($id) {
            $model = $this->_bannerFactory->create();
            $model->load($id);
            if (!$model->getBannerId()) {
                $this->messageManager->addError(__('Banner is no longer exist'));
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
