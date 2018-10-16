<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Banner;
/**
 * Class MassDelete
 * @package NVT\BannerManagement\Controller\Adminhtml\Banner
 */
class MassDelete extends \NVT\BannerManagement\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $ids = $this->getRequest()->getParam('selected');
        $model = $this->_bannerFactory->create();
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
