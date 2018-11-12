<?php
namespace AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Delete
 * @package AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons
 */
class Delete extends Generic implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        if(!$this->getObjectId()) { return []; }
        return [
                'label' => __('Delete Banner'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm( \'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
    }
}
