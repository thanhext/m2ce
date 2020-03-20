<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Menu;
/**
 * Class MassDelete
 * @package NVT\MenuManagement\Controller\Adminhtml\Menu
 */
class MassDelete extends \NVT\MenuManagement\Controller\Adminhtml\Menu
{
    public function execute()
    {
        $ids = $this->getRequest()->getParam('selected');
        $model = $this->_menuFactory->create();
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
