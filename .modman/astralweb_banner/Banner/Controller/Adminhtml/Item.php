<?php
namespace AstralWeb\Banner\Controller\Adminhtml;


use AstralWeb\Banner\Api\Data\ItemInterface;
use AstralWeb\Banner\Api\ItemRepositoryInterface;

/**
 * Class Item
 * @package AstralWeb\Banner\Controller\Adminhtml
 */
abstract class Item extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'AstralWeb_Banner::item_grid';
    /**
     * @var \AstralWeb\Banner\Model\ItemFactory
     */
    protected $_itemFactory;
    /**
     * @var ItemRepositoryInterface
     */
    protected $_itemRepository;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Item constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \AstralWeb\Banner\Model\ItemFactory $postFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \AstralWeb\Banner\Model\ItemFactory $itemFactory,
        \AstralWeb\Banner\Api\ItemRepositoryInterface $itemRepository,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {

        $this->_itemFactory         = $itemFactory;
        $this->_itemRepository      = $itemRepository;
        $this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getIndexField()
    {
        return ItemInterface::ITEM_ID;
    }

}
