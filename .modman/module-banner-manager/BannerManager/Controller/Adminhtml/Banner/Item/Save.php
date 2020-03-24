<?php
namespace T2N\BannerManager\Controller\Adminhtml\Banner\Item;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;


/**
 * Class Save
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'T2N_BannerManager::banner';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \T2N\BannerManager\Model\BannerItemRepository
     */
    protected $objectRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \T2N\BannerManager\Model\BannerItemRepository $objectRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \T2N\BannerManager\Model\BannerItemRepository $objectRepository
    ) {
        $this->dataPersistor    = $dataPersistor;
        $this->objectRepository  = $objectRepository;

        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = T2N\BannerManager\Model\Banner\Item::STATUS_ENABLED;
            }
            if (empty($data['banner_item_id'])) {
                $data['banner_item_id'] = null;
            }

            /** @var \T2N\BannerManager\Model\Banner\Item $model */
            $model = $this->_objectManager->create('T2N\BannerManager\Model\Banner\Item');

            $id = $this->getRequest()->getParam('banner_item_id');
            if ($id) {
                $model = $this->objectRepository->getById($id);
            }

            $model->setData($data);

            try {
                $this->objectRepository->save($model);
                $this->messageManager->addSuccess(__('You saved the thing.'));
                $this->dataPersistor->clear('t2n_bannermanager_banner_item');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['banner_item_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('t2n_bannermanager_banner_item', $data);
            return $resultRedirect->setPath('*/*/edit', ['banner_item_id' => $this->getRequest()->getParam('banner_item_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
