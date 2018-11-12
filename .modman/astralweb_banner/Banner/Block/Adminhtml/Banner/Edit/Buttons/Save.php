<?php
namespace AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Save
 * @package AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons
 */
class Save extends Generic implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        
        return [
            'label' => __('Save Banner'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
