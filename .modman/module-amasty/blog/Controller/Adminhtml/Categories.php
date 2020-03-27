<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml;

use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Categories
 */
abstract class Categories extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Amasty\Blog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Amasty\Blog\Helper\Url
     */
    private $urlHelper;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Amasty\Blog\Api\CategoryRepositoryInterface $categoryRepository,
        \Psr\Log\LoggerInterface $logger,
        \Amasty\Blog\Helper\Url $urlHelper,
        DataPersistorInterface $dataPersistor
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryRepository = $categoryRepository;
        $this->logger = $logger;
        $this->urlHelper = $urlHelper;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_Blog::categories');
    }

     /**
      * @return \Psr\Log\LoggerInterface
      */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return DataPersistorInterface
     */
    public function getDataPersistor()
    {
        return $this->dataPersistor;
    }

    /**
     * @return \Amasty\Blog\Helper\Url
     */
    public function getUrlHelper()
    {
        return $this->urlHelper;
    }

    /**
     * @return \Amasty\Blog\Api\CategoryRepositoryInterface
     */
    public function getCategoryRepository()
    {
        return $this->categoryRepository;
    }

    /**
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function getPageFactory()
    {
        return $this->resultPageFactory;
    }

    /**
     * @return \Magento\Framework\Registry
     */
    public function getRegistry()
    {
        return $this->coreRegistry;
    }
}
