<?php
namespace Ecommage\Blog\Block\Adminhtml\Post\Button;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
class Save extends Generic implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        
        return [
            'label' => __('Save Post'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
