<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Grid;

/**
 * Class Save
 * @package AstralWeb\Banner\Controller\Adminhtml\Grid
 */
class Save extends \AstralWeb\Banner\Controller\Adminhtml\Banner
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serialize;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \AstralWeb\Banner\Model\BannerFactory $bannerFactory
     * @param \AstralWeb\Banner\Api\BannerRepositoryInterface $bannerRepository
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Framework\Serialize\Serializer\Json $serialize
     */
    public function __construct(
        \AstralWeb\Banner\Helper\Data $helper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\App\Action\Context $context,
        \AstralWeb\Banner\Model\BannerFactory $bannerFactory,
        \AstralWeb\Banner\Api\BannerRepositoryInterface $bannerRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\Serialize\Serializer\Json $serialize
    ) {
        $this->serialize = $serialize;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($helper, $coreRegistry, $context, $bannerFactory, $bannerRepository, $resultPageFactory);
    }
    public function execute()
    {

        $data = $this->getRequest()->getPostValue();

        $indexField = $this->getIndexField();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data[$indexField])) {
                $data[$indexField] = null;
            }

            /** @var \AstralWeb\Banner\Model\BannerFactory $model */
            $model = $this->_bannerFactory->create();

            $id = $this->getRequest()->getParam($indexField);
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This Banner no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            //$this->prepareImages($data);
            $model->setData($data);
            $this->_eventManager->dispatch(
                'banner_prepare_save',
                ['banner' => $model, 'request' => $this->getRequest()]
            );

            try {

                // Save news
//                $this->_bannerRepository->save($model);
//                var_dump($model->getData()); die;
                $model->save();
                // Display success message
                $this->messageManager->addSuccess(__('The banner has been saved.'));
                $this->dataPersistor->clear('banner_data');
                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', [$indexField => $model->getId(), '_current' => true]);
                }
                // Go to grid page
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            //$this->_getSession()->setFormData($data);
            $this->dataPersistor->set('banner_data', $data);
            $this->_redirect('*/*/index', [$indexField => $model->getId()]);
            return $resultRedirect->setPath('*/*/edit', [$indexField => $model->getId()]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
