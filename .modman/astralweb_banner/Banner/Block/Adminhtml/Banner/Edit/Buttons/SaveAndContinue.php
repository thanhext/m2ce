<?php
namespace AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveAndContinueButton
 * @package AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons
 */
class SaveAndContinue extends Generic implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        
        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80,
        ];
    }
}
