<?php
namespace Ecommage\Blog\Controller\Adminhtml\Post;
/**
 * Class NewAction
 * @package Ecommage\Blog\Controller\Adminhtml\Post
 */
class NewAction extends \Ecommage\Blog\Controller\Adminhtml\Post
{
    public function execute()
    {
        $this->_forward('edit');
    }
}