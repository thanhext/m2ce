<?php
namespace Ecommage\Blog\Model;
class Post extends \Magento\Framework\Model\AbstractModel implements \Ecommage\Blog\Api\Data\PostInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'ecommage_blog_post';

    protected function _construct()
    {
        $this->_init('Ecommage\Blog\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
