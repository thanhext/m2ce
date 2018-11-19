<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Item;
/**
 * Class Index
 * @package AstralWeb\Banner\Controller\Adminhtml\Item
 */
class Form extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'AstralWeb_Banner::item_grid';
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $blockFrom = $this->_view->getLayout()->createBlock('AstralWeb\Banner\Block\Adminhtml\Item\Edit');
        $blockFrom->setData('area', 'adminhtml');
        $html = $blockFrom->toHtml();

        return $this->resultJsonFactory->create()->setData([
            'html' => $html
        ]);
    }
}
