<?php
namespace NVT\BannerManagement\Controller\Adminhtml\Item;
/**
 * Class MassStatus
 * @package NVT\BannerManagement\Controller\Adminhtml\Item
 */
class MassStatus extends \NVT\BannerManagement\Controller\Adminhtml\Item
{


    public function execute()
    {
        $ids        = $this->getRequest()->getParam('selected');
        $identities = \NVT\BannerManagement\Model\Item::CACHE_TAG . '_id';
        $status     = $this->getRequest()->getParam('status');
        if(is_array($ids)) {
            foreach ($ids as $id) {
                try {
                    $model = $this->_itemFactory->create();
                    $model->setData(array($identities => $id, 'is_active' => $status));
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
