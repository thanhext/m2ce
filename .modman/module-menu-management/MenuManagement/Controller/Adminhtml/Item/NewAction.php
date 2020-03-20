<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Item;
/**
 * Class NewAction
 * @package NVT\MenuManagement\Controller\Adminhtml\Item
 */
class NewAction extends \NVT\MenuManagement\Controller\Adminhtml\Item
{
    public function execute()
    {
        $this->_forward('edit');
    }
}