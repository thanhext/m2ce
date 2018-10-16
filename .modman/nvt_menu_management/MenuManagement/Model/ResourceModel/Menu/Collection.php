<?php
namespace NVT\MenuManagement\Model\ResourceModel\Menu;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('NVT\MenuManagement\Model\Menu','NVT\MenuManagement\Model\ResourceModel\Menu');
    }
}
