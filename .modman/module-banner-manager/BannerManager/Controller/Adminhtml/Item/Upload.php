<?php

namespace T2N\BannerManager\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Theme\Model\Design\Config\FileUploader\FileProcessor;
use T2N\BannerManager\Model\Banner\ItemFactory;
use T2N\BannerManager\Model\ImageUploader;

/**
 * File Uploads Action Controller
 *
 * @api
 * @since 100.1.0
 */
class Upload extends BannerItem
{
    /**
     * Image uploader
     *
     * @var \T2N\BannerManager\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * Upload constructor.
     *
     * @param Context                $context
     * @param DataPersistorInterface $dataPersistor
     * @param FileProcessor          $fileProcessor
     * @param ItemFactory            $itemFactory
     * @param PageFactory            $resultPageFactory
     * @param ImageUploader          $imageUploader
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        FileProcessor $fileProcessor,
        ItemFactory $itemFactory,
        PageFactory $resultPageFactory,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context, $itemFactory, $dataPersistor, $resultPageFactory);
        $this->imageUploader = $imageUploader;
    }

    /**
     * @inheritDoc
     * @since 100.1.0
     */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('image');
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
