<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Item;
/**
 * Class Index
 * @package AstralWeb\Banner\Controller\Adminhtml\Item
 */
class Index extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'AstralWeb_Banner::item_grid';
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('All Items'));
        return $resultPage;
    }

}