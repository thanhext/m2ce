<?php

namespace T2N\BannerManager\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Theme\Model\Design\Config\FileUploader\FileProcessor;
use T2N\BannerManager\Model\Banner\ItemFactory;

/**
 * File Uploads Action Controller
 *
 * @api
 * @since 100.1.0
 */
class Upload extends BannerItem
{
    /**
     * @var FileProcessor
     * @since 100.1.0
     */
    protected $fileProcessor;

    /**
     * Upload constructor.
     *
     * @param Context       $context
     * @param Registry      $coreRegistry
     * @param FileProcessor $fileProcessor
     * @param ItemFactory   $itemFactory
     * @param PageFactory   $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FileProcessor $fileProcessor,
        ItemFactory $itemFactory,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context, $itemFactory, $coreRegistry, $resultPageFactory);
        $this->fileProcessor = $fileProcessor;
    }

    /**
     * @inheritDoc
     * @since 100.1.0
     */
    public function execute()
    {
        $result = $this->fileProcessor->saveToTmp(key($_FILES));
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
