<?php

namespace NVT\MenuManagement\Controller\Adminhtml\Menu;
/**
 * Class Delete
 * @package NVT\MenuManagement\Controller\Adminhtml\Menu
 */
class Delete extends \NVT\MenuManagement\Controller\Adminhtml\Menu
{
    public function execute()
    {
        $identities = \NVT\MenuManagement\Model\Menu::CACHE_TAG . '_id';
        $id = $this->getRequest()->getParam($identities);
        if($id) {
            $model = $this->_menuFactory->create();
            $model->load($id);
            if (!$model->getMenuId()) {
                $this->messageManager->addError(__('Menu is no longer exist'));
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
