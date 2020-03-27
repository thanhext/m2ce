<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml;

use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Authors
 */
abstract class Authors extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \Amasty\Blog\Api\AuthorRepositoryInterface
     */
    private $authorRepository;

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
        \Amasty\Blog\Api\AuthorRepositoryInterface $authorRepository,
        DataPersistorInterface $dataPersistor,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->authorRepository = $authorRepository;
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
        return $this->_authorization->isAllowed('Amasty_Blog::authors');
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
     * @return \Amasty\Blog\Api\AuthorRepositoryInterface
     */
    public function getAuthorRepository()
    {
        return $this->authorRepository;
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
