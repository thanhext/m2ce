<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Menu;
/**
 * Class Save
 * @package NVT\MenuManagement\Controller\Adminhtml\Menu
 */
class Save extends \NVT\MenuManagement\Controller\Adminhtml\Menu
{
    public function execute()
    {
        $data       = $this->getRequest()->getParam('menu');
        $isPost     = $this->getRequest()->getPost();
        $identities = \NVT\MenuManagement\Model\Menu::CACHE_TAG . '_id';
        if ($isPost) {
            $model = $this->_menuFactory->create();
            $model->setData($data);
            $id = $model->getData($identities);
            try {
                // Save news
                $model->save();
                // Display success message
                $this->messageManager->addSuccess(__('The Menu has been saved.'));
                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', [$identities => $id, '_current' => true]);
                    return;
                }
                // Go to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/index', [$identities => $id]);
        }
    }

}
