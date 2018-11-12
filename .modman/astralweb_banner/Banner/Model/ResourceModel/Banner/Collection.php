<?php
namespace AstralWeb\Banner\Model\ResourceModel\Banner;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('AstralWeb\Banner\Model\Banner','AstralWeb\Banner\Model\ResourceModel\Banner');
    }
}
