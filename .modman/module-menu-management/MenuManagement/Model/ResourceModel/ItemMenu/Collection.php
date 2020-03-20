<?php
namespace NVT\MenuManagement\Model\ResourceModel\ItemMenu;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('NVT\MenuManagement\Model\ItemMenu','NVT\MenuManagement\Model\ResourceModel\ItemMenu');
    }
}
