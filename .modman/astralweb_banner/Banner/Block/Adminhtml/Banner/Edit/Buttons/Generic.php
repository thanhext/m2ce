<?php
namespace AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons;

/**
 * Class Generic
 * @package AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons
 */
class Generic
{
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->context = $context;    
    }
    
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }    
    
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['object_id' => $this->getObjectId()]);
    }   
    
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }    
    
    public function getObjectId()
    {
        return $this->context->getRequest()->getParam('banner_id');
    }     
}
