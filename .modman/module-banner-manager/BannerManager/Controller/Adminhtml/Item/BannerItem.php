<?php

namespace T2N\BannerManager\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use T2N\BannerManager\Model\Banner\ItemFactory;

abstract class BannerItem extends Action
{
    const ADMIN_RESOURCE = 'Ecommage_BannerManager::banner';
    /**
     * @var ItemFactory
     */
    protected $_itemFactory;
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
     * @param Context        $context
     * @param ItemFactory $itemFactory
     * @param Registry       $coreRegistry
     * @param PageFactory    $resultPageFactory
     */
    public function __construct(
        Context $context,
        ItemFactory $itemFactory,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {

        $this->_itemFactory    = $itemFactory;
        $this->_coreRegistry      = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
}
