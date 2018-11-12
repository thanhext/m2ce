<?php
namespace AstralWeb\EzShipping\Model\ResourceModel\Ez;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('AstralWeb\EzShipping\Model\Ez','AstralWeb\EzShipping\Model\ResourceModel\Ez');
    }
}
