<?php

namespace NVT\BannerManagement\Controller\Adminhtml\Item;
/**
 * Class Delete
 * @package NVT\BannerManagement\Controller\Adminhtml\Item
 */
class Delete extends \NVT\BannerManagement\Controller\Adminhtml\Item
{
    public function execute()
    {
        $identities = \NVT\BannerManagement\Model\Item::CACHE_TAG . '_id';
        $id = $this->getRequest()->getParam($identities);
        if($id) {
            $model = $this->_itemFactory->create();
            $model->load($id);
            if (!$model->getItemId()) {
                $this->messageManager->addError(__('Item is no longer exist'));
            } else {
                try {
                    $model->delete();
                    $this->messageManager->addSuccess(__('Deleted Successfully!'));
                    $this->_redirect('*/*/');
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                    $this->_redirect('*/*/edit', [$identities => $id]);
                }
            }
        }
    }

}
