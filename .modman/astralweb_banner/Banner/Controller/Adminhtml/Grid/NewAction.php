<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Grid;

/**
 * Class NewAction
 * @package AstralWeb\Banner\Controller\Adminhtml\Grid
 */
class NewAction extends \AstralWeb\Banner\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $this->_forward('edit');
    }
}