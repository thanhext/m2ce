<?php
namespace AstralWeb\Banner\Model\ResourceModel;
/**
 * Class Item
 * @package AstralWeb\Banner\Model\ResourceModel
 */
class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('banner_item','item_id');
    }
}
