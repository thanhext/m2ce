<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Layout;

class Update extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'AstralWeb_Banner::banner_grid';
    /**
     * @var \AstralWeb\Banner\Helper\Data
     */
    protected $helper;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Update constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \AstralWeb\Banner\Helper\Data $helper,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $allOptions = $this->helper->getAllPagesOptions();
        // TODO: Implement execute() method.
        return $resultJson->setData([
            'options' => $allOptions
        ]);
    }




    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
