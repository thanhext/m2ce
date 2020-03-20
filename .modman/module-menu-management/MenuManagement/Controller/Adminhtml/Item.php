<?php
namespace NVT\MenuManagement\Controller\Adminhtml;

abstract class Item extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'NVT_MenuManagement::item_management';

    protected $_itemFactory;
    protected $_coreRegistry;
    protected $_resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \NVT\MenuManagement\Model\ItemFactory $itemFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {

        $this->_itemFactory         = $itemFactory;
        $this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;
        parent::__construct($context);
    }

}
