<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Item;

use Magento\Framework\Controller\ResultFactory;
/**
 * Class Upload
 * @package AstralWeb\Banner\Controller\Adminhtml\Item
 */
class Upload extends \Magento\Backend\App\Action
{
    /**
     * Image uploader
     *
     * @var \AstralWeb\Banner\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \AstralWeb\Banner\Model\ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \AstralWeb\Banner\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
{
    return $this->_authorization->isAllowed('AstralWeb_Banner::item_grid');
}

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
{
    try {
        $result = $this->imageUploader->saveFileToTmpDir('image_desktop');

        $result['cookie'] = [
            'name' => $this->_getSession()->getName(),
            'value' => $this->_getSession()->getSessionId(),
            'lifetime' => $this->_getSession()->getCookieLifetime(),
            'path' => $this->_getSession()->getCookiePath(),
            'domain' => $this->_getSession()->getCookieDomain(),
        ];
    } catch (\Exception $e) {
        $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
    }
    return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
}
}