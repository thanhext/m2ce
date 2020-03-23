<?php

namespace T2N\BannerManager\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use T2N\BannerManager\Model\BannerFactory;

abstract class Banner extends Action
{
    const ADMIN_RESOURCE = 'T2N_BannerManager::banner';
    /**
     * @var BannerFactory
     */
    protected $_bannerFactory;
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Banner constructor.
     *
     * @param Context       $context
     * @param BannerFactory $bannerFactory
     * @param Registry      $coreRegistry
     * @param PageFactory   $resultPageFactory
     */
    public function __construct(
        Context $context,
        BannerFactory $bannerFactory,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {

        $this->_bannerFactory     = $bannerFactory;
        $this->_coreRegistry      = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
}
