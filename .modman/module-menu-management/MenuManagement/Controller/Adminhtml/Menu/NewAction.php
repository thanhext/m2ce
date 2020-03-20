<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Menu;
/**
 * Class NewAction
 * @package NVT\MenuManagement\Controller\Adminhtml\Menu
 */
class NewAction extends \NVT\MenuManagement\Controller\Adminhtml\Menu
{
    public function execute()
    {
        $this->_forward('edit');
    }
}