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
     * @param LoggerInterface        $logger
     * @param ItemFactory            $itemFactory
     * @param PageFactory            $resultPageFactory
     * @param BannerRepository       $bannerRepository
     * @param DataObjectHelper       $dataObjectHelper
     * @param DataPersistorInterface $dataPersistor
     * @param BannerItemRepository   $bannerItemRepository
     * @param ItemInterfaceFactory   $bannerItemDataFactory
     * @param JsonFactory            $resultJsonFactory
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        ItemFactory $itemFactory,
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
        parent::__construct($context, $itemFactory, $dataPersistor, $resultPageFactory);
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $error        = false;
        $data         = $this->getRequest()->getPostValue();
        $bannerId     = (int)$this->getRequest()->getParam('banner_id', 0);
        $bannerItemId = (int)$this->getRequest()->getParam('entity_id', 0);
        $message      = __('We can\'t change banner item right now.');
        if ($data && $bannerId) {
            $data['banner_id'] = $bannerId;
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Status::STATUS_ENABLED;
            }

            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            if (!empty($data['image'])) {
                $this->moveFileFromTmp($data);
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
            } catch (\Exception $e) {
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
    protected function moveFileFromTmp(&$data)
    {
        if (!isset($data['image'][0])) {
            return $data;
        }

        $image = $data['image'][0];
        if (isset($image['name']) && isset($image['tmp_name'])) {
            /** @var \T2N\BannerManager\Model\ImageUploader imageUploader */
            $this->imageUploader = $this->_objectManager->get(
                'T2N\BannerManager\BannerImageUpload'
            );
            $basePath            = $this->imageUploader->getBasePath();
            $data['image']       = $basePath . DIRECTORY_SEPARATOR . $image['name'];
            $data['media_type']  = $image['type'];
            $this->imageUploader->moveFileFromTmp($data['image']);
        } elseif (isset($image['name']) && !isset($image['tmp_name'])) {
            $p                  = strpos($image['url'], 'pub/media');
            $data['media_type'] = $image['type'];
            if ($p != -1) {
                $data['image'] = substr($image['url'], $p + 10);
            }
        }

        return $data;
    }
}
