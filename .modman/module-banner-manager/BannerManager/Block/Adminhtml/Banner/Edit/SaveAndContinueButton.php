<?php

namespace T2N\BannerManager\Block\Adminhtml\Banner\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveAndContinueButton
 */
class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        if ($this->getId()) {
            return [];
        }
        
        return [
            'label'          => __('Save and add banner item'),
            'class'          => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order'     => 80,
        ];
    }
}
