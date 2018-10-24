<?php
namespace Ecommage\Blog\Controller\Adminhtml;
use Ecommage\Blog\Api\PostRepositoryInterface;

/**
 * Class Post
 * @package Ecommage\Blog\Controller\Adminhtml
 */
abstract class Post extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Ecommage_Blog::blog_post';
    /**
     * @var \Ecommage\Blog\Model\PostFactory
     */
    protected $_postFactory;
    /**
     * @var PostRepositoryInterface
     */
    protected $_postRepository;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Post constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Ecommage\Blog\Model\PostFactory $postFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Ecommage\Blog\Model\PostFactory $postFactory,
        \Ecommage\Blog\Api\PostRepositoryInterface $postRepository,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {

        $this->_postFactory         = $postFactory;
        $this->_postRepository      = $postRepository;
        $this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getIdentities()
    {
        return \Ecommage\Blog\Helper\Data::FIELD_ID;
    }

}
