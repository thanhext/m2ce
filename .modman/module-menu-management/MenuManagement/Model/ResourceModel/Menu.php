<?php
namespace NVT\MenuManagement\Model\ResourceModel;
class Menu extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('menumanagement_menu','menu_id');
    }
}
