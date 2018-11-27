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
     * @var \AstralWeb\Banner\Helper\Data
     */
    protected $helper;
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
     * @param \AstralWeb\Banner\Helper\Data $helper
     * @param \AstralWeb\Banner\Model\BannerFactory $bannerFactory
     * @param BannerRepositoryInterface $bannerRepository
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \AstralWeb\Banner\Helper\Data $helper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\App\Action\Context $context,
        \AstralWeb\Banner\Model\BannerFactory $bannerFactory,
        \AstralWeb\Banner\Api\BannerRepositoryInterface $bannerRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->helper               = $helper;
        $this->_bannerFactory       = $bannerFactory;
        $this->_bannerRepository    = $bannerRepository;
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

    /**
     * @return mixed
     */
    public function getThemeId()
    {
        return $this->helper->getThemeId();
    }
}
