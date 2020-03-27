<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml\Categories;

use Amasty\Blog\Model\Categories;
use Amasty\Blog\Block\Sidebar\Category\TreeRenderer;

/**
 * Class Save
 */
class Save extends \Amasty\Blog\Controller\Adminhtml\Categories
{
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('category_id');
            try {
                $model = $this->getCategoryRepository()->getCategory();
                $inputFilter = new \Zend_Filter_Input([], [], $data);
                $data = $inputFilter->getUnescaped();

                if ($id) {
                    $model = $this->getCategoryRepository()->getById($id);
                }

                $model->addData($data);
                if ($model->getParentId() && $model->getParentCategory()->getLevel() + 1 > TreeRenderer::LEVEL_LIMIT) {
                    $this->getMessageManager()->addErrorMessage(
                        __(
                            'You have exceeded the category tree depth which is limited by %1.',
                            TreeRenderer::LEVEL_LIMIT
                        )
                    );
                    $this->redirectById($id);

                    return;
                }
                $this->_getSession()->setPageData($model->getData());
                if (!$model->getUrlKey()) {
                    $model->setUrlKey($this->getUrlHelper()->generate($model->getName()));
                }
                $this->getCategoryRepository()->save($model);
                $this->getMessageManager()->addSuccessMessage(__('You saved the item.'));
                $this->_getSession()->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getCategoryId()]);

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                $this->getDataPersistor()->set(Categories::PERSISTENT_NAME, $data);
                $this->redirectById($id);

                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                $this->getDataPersistor()->set(Categories::PERSISTENT_NAME, $data);
                $this->redirectById($id);

                return;
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->getLogger()->critical($e);
                $this->_getSession()->setPageData($data);
                $this->_redirect('*/*/edit', ['category_id' => $id]);

                return;
            }
        }
        $this->_redirect('*/*/');
    }

    private function redirectById($id)
    {
        if (!empty($id)) {
            $this->_redirect('*/*/edit', ['id' => $id]);
        } else {
            $this->_redirect('*/*/new');
        }
    }
}
