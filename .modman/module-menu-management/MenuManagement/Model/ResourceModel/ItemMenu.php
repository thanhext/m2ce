<?php
namespace NVT\MenuManagement\Model\ResourceModel;
class ItemMenu extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('menumanagement_menu_item','entity_id');
    }
}
