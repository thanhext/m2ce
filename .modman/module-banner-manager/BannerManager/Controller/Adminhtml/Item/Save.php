<?php

namespace T2N\BannerManager\Controller\Adminhtml\Item;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use T2N\BannerManager\Api\Data\ItemInterfaceFactory;
use T2N\BannerManager\Model\Banner\ItemFactory;
use T2N\BannerManager\Model\BannerRepository;
use T2N\BannerManager\Model\BannerItemRepository;
use Psr\Log\LoggerInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Api\DataObjectHelper;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var BannerItemRepository
     */
    protected $bannerItemRepository;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var ItemInterfaceFactory
     */
    private $bannerItemDataFactory;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var BannerRepository
     */
    private $bannerRepository;
    /**
     * @var
     */
    private $imageUploader;

    /**
     * Save constructor.
     *
     * @param Context                $context
     * @param ItemFactory            $itemFactory
     * @param Registry               $coreRegistry
     * @param PageFactory            $resultPageFactory
     * @param BannerRepository       $bannerRepository
     * @param DataPersistorInterface $dataPersistor
     * @param BannerItemRepository   $bannerItemRepository
     * @param JsonFactory            $resultJsonFactory
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        ItemFactory $itemFactory,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        BannerRepository $bannerRepository,
        DataObjectHelper $dataObjectHelper,
        DataPersistorInterface $dataPersistor,
        BannerItemRepository $bannerItemRepository,
        ItemInterfaceFactory $bannerItemDataFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->logger                = $logger;
        $this->dataPersistor         = $dataPersistor;
        $this->dataObjectHelper      = $dataObjectHelper;
        $this->bannerRepository      = $bannerRepository;
        $this->resultJsonFactory     = $resultJsonFactory;
        $this->bannerItemRepository  = $bannerItemRepository;
        $this->bannerItemDataFactory = $bannerItemDataFactory;
        parent::__construct($context, $itemFactory, $coreRegistry, $resultPageFactory);
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $error          = false;
        $data = $this->getRequest()->getPostValue();
        $bannerId       = $this->getRequest()->getParam('banner_id', false);
        $bannerItemId   = $this->getRequest()->getParam('entity_id', false);
        $message        = __('We can\'t change banner item right now.');
        if ($data && $bannerId) {
            $data['banner_id'] = $bannerId;
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Status::STATUS_ENABLED;
            }

            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            if (!empty($data['image'])) {
                $data['image'] = $this->moveFileFromTmp($data['image']);
            }

            /** @var \T2N\BannerManager\Model\Banner\Item $model */
            $model = $this->_itemFactory->create();
            if ($bannerItemId) {
                $model = $this->bannerItemRepository->getById($bannerItemId);
            }

            $model->setData($data);
            try {
                $savedBannerItem = $this->bannerItemRepository->save($model);
                if ($bannerItemId) {
                    $message = __('Banner Item has been updated.');
                } else {
                    $bannerItemId = $savedBannerItem->getId();
                    $message      = __('New banner item has been added.');
                }
            } catch (LocalizedException $e) {
                $error   = true;
                $message = __($e->getMessage());
            } catch (Exception $e) {
                $error   = true;
                $message = __('We can\'t change banner item right now.');
            }
        }
        $bannerItemId = empty($bannerItemId) ? null : $bannerItemId;
        $resultJson   = $this->resultJsonFactory->create();
        $resultJson->setData(
            [
                'message' => $message,
                'error'   => $error,
                'data'    => [
                    'entity_id' => $bannerItemId
                ]
            ]
        );

        return $resultJson;
    }

    /**
     * @param $image
     *
     * @return mixed
     */
    protected function moveFileFromTmp($image)
    {
        if (isset($image[0]['name']) && isset($image[0]['tmp_name'])) {
            $image = $image[0]['name'];
            /** @var \T2N\BannerManager\Model\ImageUploader imageUploader */
            $this->imageUploader = $this->_objectManager->get(
                'T2N\BannerManager\BannerImageUpload'
            );
            $this->imageUploader->moveFileFromTmp($image);
        }

        return $image;
    }
}
