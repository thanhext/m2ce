<?php
namespace NVT\BannerManagement\Controller\Adminhtml;

abstract class Banner extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'NVT_BannerManagement::banner_management';

    protected $_bannerFactory;
    protected $_coreRegistry;
    protected $_resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \NVT\BannerManagement\Model\BannerFactory $bannerFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {

        $this->_bannerFactory     = $bannerFactory;
        $this->_coreRegistry    = $coreRegistry;
        $this->_resultPageFactory=$resultPageFactory;

        parent::__construct($context);
    }

}
