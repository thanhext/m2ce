<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Menu;
/**
 * Class MassStatus
 * @package NVT\MenuManagement\Controller\Adminhtml\Menu
 */
class MassStatus extends \NVT\MenuManagement\Controller\Adminhtml\Menu
{


    public function execute()
    {
        $ids        = $this->getRequest()->getParam('selected');
        $identities = \NVT\MenuManagement\Model\Menu::CACHE_TAG . '_id';
        $status     = $this->getRequest()->getParam('status');
        if(is_array($ids)) {
            foreach ($ids as $id) {
                try {
                    $model = $this->_menuFactory->create();
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
