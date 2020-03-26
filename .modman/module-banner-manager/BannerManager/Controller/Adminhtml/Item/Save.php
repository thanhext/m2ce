<?php

namespace T2N\BannerManager\Controller\Adminhtml\Item;

use T2N\BannerManager\Model\Banner\ItemFactory;
use T2N\BannerManager\Model\BannerItemRepository;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use T2N\BannerManager\Model\System\Config\Status;

/**
 * Class Save
 */
class Save extends BannerItem
{

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var BannerItemRepository
     */
    protected $bannerItemRepository;

    public function __construct(
        Context $context,
        ItemFactory $itemFactory,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        DataPersistorInterface $dataPersistor,
        BannerItemRepository $bannerItemRepository
    ) {
        $this->dataPersistor     = $dataPersistor;
        $this->bannerItemRepository = $bannerItemRepository;
        parent::__construct($context, $itemFactory, $coreRegistry, $resultPageFactory);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Status::STATUS_ENABLED;
            }

            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            /** @var \T2N\BannerManager\Model\Banner $model */
            $model = $this->_itemFactory->create();
            $id    = $this->getRequest()->getParam('id');
            if ($id) {
                $model = $this->bannerItemRepository->getById($id);
            }

            $model->setData($data);
            try {
                $this->bannerItemRepository->save($model);
                $this->messageManager->addSuccess(__('You saved the thing.'));
                $this->dataPersistor->clear('banner_item');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('banner_item', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
