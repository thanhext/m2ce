<?php
namespace NVT\MenuManagement\Controller\Adminhtml;

abstract class Menu extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'NVT_MenuManagement::menu_management';

    protected $_menuFactory;
    protected $_coreRegistry;
    protected $_resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \NVT\MenuManagement\Model\MenuFactory $menuFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {

        $this->_menuFactory     = $menuFactory;
        $this->_coreRegistry    = $coreRegistry;
        $this->_resultPageFactory=$resultPageFactory;

        parent::__construct($context);
    }

}
