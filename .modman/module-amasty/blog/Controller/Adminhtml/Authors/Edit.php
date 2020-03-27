<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml\Authors;

/**
 * Class Edit
 */
class Edit extends \Amasty\Blog\Controller\Adminhtml\Authors
{
    const CURRENT_AMASTY_BLOG_TAG = 'current_amasty_blog_author';

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = $this->getAuthorRepository()->getAuthorModel();

        if ($id) {
            try {
                $model = $this->getAuthorRepository()->getById($id);
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                $this->_redirect('*/*');

                return;
            }
        }

        // set entered data if was error when we do save
        $data = $this->_getSession()->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->getRegistry()->register(self::CURRENT_AMASTY_BLOG_TAG, $model);
        $this->initAction();
        if ($model->getId()) {
            $title = __('Edit Author `%1`', $model->getName());
        } else {
            $title = __("Add New Author");
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }

    /**
     * @return $this
     */
    private function initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Amasty_Blog::authors')->_addBreadcrumb(
            __('Amasty Blog Authors'),
            __('Amasty Blog Authors')
        );

        return $this;
    }
}
