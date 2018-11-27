<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Grid;

use Magento\Theme\Helper\Storage;
/**
 * Class Edit
 * @package AstralWeb\Banner\Controller\Adminhtml\Grid
 */
class Edit extends \AstralWeb\Banner\Controller\Adminhtml\Banner
{

    public function execute()
    {
        $indexField = $this->getIndexField();
        $id = $this->getRequest()->getParam($indexField);
        $themeId = $this->getRequest()->getParam(Storage::PARAM_THEME_ID);

        if (!$themeId) {
            return $this->_redirect('*/*/', [Storage::PARAM_THEME_ID => $this->getThemeId()]);
        }

        $model = $this->_bannerFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Banner no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }
        // Restore previously entered form data from session
        $data = $this->_session->getPostData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('current_banner', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('AstralWeb_Banner::banner_grid');
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Edit'));
        return $resultPage;
    }

}
