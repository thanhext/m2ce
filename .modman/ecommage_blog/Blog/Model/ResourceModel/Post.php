<?php
namespace Ecommage\Blog\Model\ResourceModel;
class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('ecommage_post','post_id');
    }
}
