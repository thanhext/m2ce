<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Item;

/**
 * Class Save
 * @package AstralWeb\Banner\Controller\Adminhtml\Item
 */
class Save extends \AstralWeb\Banner\Controller\Adminhtml\Item
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
     * @param \AstralWeb\Banner\Model\ItemFactory $itemFactory
     * @param \AstralWeb\Banner\Api\ItemRepositoryInterface $itemRepository
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Framework\Serialize\Serializer\Json $serialize
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \AstralWeb\Banner\Model\ItemFactory $itemFactory,
        \AstralWeb\Banner\Api\ItemRepositoryInterface $itemRepository,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\Serialize\Serializer\Json $serialize
    ) {
        $this->serialize = $serialize;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context, $itemFactory, $itemRepository, $coreRegistry, $resultPageFactory);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
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

            /** @var \AstralWeb\Banner\Model\ItemFactory $model */
            $model = $this->_itemFactory->create();

            $id = $this->getRequest()->getParam($indexField);
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This Item no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $model->setData($data);
            $this->_eventManager->dispatch(
                'banner_item_prepare_save',
                ['item' => $model, 'request' => $this->getRequest()]
            );

            try {

                // Save news
//                $this->_bannerRepository->save($model);
//                var_dump($model->getData()); die;
                $model->save();
                // Display success message
                $this->messageManager->addSuccess(__('The item has been saved.'));
                $this->dataPersistor->clear('banner_item_data');
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
            $this->dataPersistor->set('banner_item_data', $data);
            $this->_redirect('*/*/index', [$indexField => $model->getId()]);
            return $resultRedirect->setPath('*/*/edit', [$indexField => $model->getId()]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
