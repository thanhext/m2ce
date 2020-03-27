<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml;

use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Tags
 */
abstract class Tags extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \Amasty\Blog\Api\TagRepositoryInterface
     */
    private $tagRepository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Amasty\Blog\Api\TagRepositoryInterface $tagRepository,
        DataPersistorInterface $dataPersistor,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->tagRepository = $tagRepository;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_Blog::tags');
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
     * @return \Amasty\Blog\Api\TagRepositoryInterface
     */
    public function getTagRepository()
    {
        return $this->tagRepository;
    }

    /**
     * @return \Magento\Framework\Registry
     */
    public function getRegistry()
    {
        return $this->coreRegistry;
    }

    /**
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function getPageFactory()
    {
        return $this->resultPageFactory;
    }
}
