<?php
namespace Ecommage\Blog\Controller\Adminhtml\Post;
/**
 * Class Save
 * @package Ecommage\Blog\Controller\Adminhtml\Post
 */
class Save extends \Ecommage\Blog\Controller\Adminhtml\Post
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var \Ecommage\Blog\Helper\Image
     */
    protected $imageHelper;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serialize;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Ecommage\Blog\Model\PostFactory $postFactory
     * @param \Ecommage\Blog\Api\PostRepositoryInterface $postRepository
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Ecommage\Blog\Model\PostFactory $postFactory,
        \Ecommage\Blog\Api\PostRepositoryInterface $postRepository,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        \Ecommage\Blog\Helper\Image $imageHelper
    ) {
        $this->serialize = $serialize;
        $this->imageHelper = $imageHelper;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context, $postFactory, $postRepository, $coreRegistry, $resultPageFactory);
    }
    public function execute()
    {

        $data = $this->getRequest()->getPostValue();

        $identities = $this->getIdentities();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data[$identities])) {
                $data[$identities] = null;
            }

            /** @var \Ecommage\Blog\Model\PostFactory $model */
            $model = $this->_postFactory->create();

            $id = $this->getRequest()->getParam($identities);
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            //$this->prepareImages($data);
            $model->setData($data);
            $this->_eventManager->dispatch(
                'blog_post_prepare_save',
                ['post' => $model, 'request' => $this->getRequest()]
            );

            try {

                // Save news
//                $this->_postRepository->save($model);
//                var_dump($model->getData()); die;
                $model->save();
                // Display success message
                $this->messageManager->addSuccess(__('The post has been saved.'));
                $this->dataPersistor->clear('blog_post');
                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', [$identities => $model->getId(), '_current' => true]);
                }
                // Go to grid page
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            //$this->_getSession()->setFormData($data);
            $this->dataPersistor->set('blog_post', $data);
            $this->_redirect('*/*/index', [$identities => $model->getId()]);
            return $resultRedirect->setPath('*/*/edit', [$identities => $model->getId()]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function prepareImages(&$data)
    {
        if (isset($data['featured_src']) && is_array($data['featured_src'])) {
            $imageHelper = $this->_objectManager->create(\Ecommage\Blog\Helper\Image::class);
            foreach ($data['featured_src'] as $key => $image) {
                $file = $imageHelper->moveImageFromTmp($image['file']);
                $data['featured_src'][$key]['file'] = $file;
                $data['featured_src'][$key]['url'] = $imageHelper->getMediaUrl($file);
            }
            $data['featured_src'] = $this->serialize->serialize($data['featured_src']);
        }
        return $data;
    }
}
