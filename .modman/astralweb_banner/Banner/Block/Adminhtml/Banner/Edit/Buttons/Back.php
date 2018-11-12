<?php
namespace AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class BackButton
 * @package AstralWeb\Banner\Block\Adminhtml\Banner\Edit
 */
class Back extends Generic implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10    
        ];
    }
}
