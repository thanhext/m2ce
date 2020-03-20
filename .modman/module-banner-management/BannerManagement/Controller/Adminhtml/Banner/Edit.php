<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Banner;
/**
 * Class Edit
 * @package NVT\BannerManagement\Controller\Adminhtml\Banner
 */
class Edit extends \NVT\BannerManagement\Controller\Adminhtml\Banner
{

    public function execute()
    {
        $identities = \NVT\BannerManagement\Model\Banner::CACHE_TAG . '_id';
        $id = $this->getRequest()->getParam($identities);
        $model = $this->_bannerFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getBannerId()) {
                $this->messageManager->addError(__('This Banner no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // Restore previously entered form data from session
        $data = $this->_session->getBannerData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('banner', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('NVT_BannerManagement::banner_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Edit'));
        return $resultPage;
    }

}
