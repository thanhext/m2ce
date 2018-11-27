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
        $layout = $this->_view->getLayout();
        $layout->getUpdate()->addHandle('banner_item_edit')->load();

        $html = $layout->getBlock('banner_item_form')->toHtml();

        return $this->resultJsonFactory->create()->setData([
            'html' => $html
        ]);
    }
}
