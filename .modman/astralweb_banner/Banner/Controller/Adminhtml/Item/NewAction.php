<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Item;

/**
 * Class NewAction
 * @package AstralWeb\Banner\Controller\Adminhtml\Item
 */
class NewAction extends \AstralWeb\Banner\Controller\Adminhtml\Item
{
    public function execute()
    {
        $this->_forward('edit');
    }
}