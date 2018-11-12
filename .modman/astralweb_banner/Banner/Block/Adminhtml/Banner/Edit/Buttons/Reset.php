<?php
namespace AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResetButton
 * @package AstralWeb\Banner\Block\Adminhtml\Banner\Edit
 */
class Reset extends Generic implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
    }
}
