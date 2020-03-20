<?php
namespace Ecommage\Blog\Controller\Adminhtml\Post;
/**
 * Class Edit
 * @package Ecommage\Blog\Controller\Adminhtml\Post
 */
class Edit extends \Ecommage\Blog\Controller\Adminhtml\Post
{

    public function execute()
    {
        $identities = $this->getIdentities();
        $id = $this->getRequest()->getParam($identities);
        $model = $this->_postFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Post no longer exists.'));
                return $this->_redirect('*/*/');
            }
//            $serialize = $this->_objectManager->get(\Magento\Framework\Serialize\Serializer\Json::class);
//            $images = $model->getData('featured_src');
//            $img = $serialize->unserialize($images);
//            var_dump($img);
//            $model->setData('featured_src', $images);
//var_dump($model->getData('featured_src')); die;
//            if ($images = $model->getData('featured_src')) {
//                if (strpos($images, ',')) {
//                    $files = explode(',', $images);
//                } else {
//                    $files[]['file'] = $images;
//                }
//                $data = [];
//                foreach ($files as $file) {
//                    $data[] = $this->_imageHelper->prepareImage($file);
//                }
//                $model->setData('featured_src', $files);
//            }

        }
//        var_dump($model->getData()); die;
        // Restore previously entered form data from session
        $data = $this->_session->getPostData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('current_post', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Ecommage_Blog::blog_post');
        $resultPage->getConfig()->getTitle()->prepend(__('Post Edit'));
        return $resultPage;
    }

}
