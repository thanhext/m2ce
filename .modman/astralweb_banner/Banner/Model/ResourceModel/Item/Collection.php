<?php
namespace AstralWeb\Banner\Model\ResourceModel\Item;
/**
 * Class Collection
 * @package AstralWeb\Banner\Model\ResourceModel\Item
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('AstralWeb\Banner\Model\Item','AstralWeb\Banner\Model\ResourceModel\Item');
    }
}
