<?php
namespace AstralWeb\Banner\Controller\Adminhtml;


use AstralWeb\Banner\Api\Data\BannerInterface;
use AstralWeb\Banner\Api\BannerRepositoryInterface;

/**
 * Class Banner
 * @package AstralWeb\Banner\Controller\Adminhtml
 */
abstract class Banner extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'AstralWeb_Banner::banner_grid';
    /**
     * @var \AstralWeb\Banner\Model\BannerFactory
     */
    protected $_bannerFactory;
    /**
     * @var BannerRepositoryInterface
     */
    protected $_bannerRepository;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Banner constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \AstralWeb\Banner\Model\BannerFactory $postFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \AstralWeb\Banner\Model\BannerFactory $bannerFactory,
        \AstralWeb\Banner\Api\BannerRepositoryInterface $bannerRepository,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {

        $this->_bannerFactory         = $bannerFactory;
        $this->_bannerRepository      = $bannerRepository;
        $this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getIndexField()
    {
        return BannerInterface::BANNER_ID;
    }

}
