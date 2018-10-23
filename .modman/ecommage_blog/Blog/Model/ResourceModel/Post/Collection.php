<?php
namespace Ecommage\Blog\Model\ResourceModel\Post;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Ecommage\Blog\Model\Post','Ecommage\Blog\Model\ResourceModel\Post');
    }
}
