<?php

namespace T2N\BannerManager\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Theme\Model\Design\Config\FileUploader\FileProcessor;
use T2N\BannerManager\Controller\Adminhtml\Banner;
use T2N\BannerManager\Model\BannerFactory;

/**
 * File Uploads Action Controller
 *
 * @api
 * @since 100.1.0
 */
class Upload extends Banner
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
     * @param BannerFactory $bannerFactory
     * @param PageFactory   $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FileProcessor $fileProcessor,
        BannerFactory $bannerFactory,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context, $bannerFactory, $coreRegistry, $resultPageFactory);
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
