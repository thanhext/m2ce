<?php
namespace NVT\MenuManagement\Model\ResourceModel;
class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('menumanagement_item','item_id');
    }
    /**
     * Perform actions after object load
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $menuIds = $this->loadMenuByItem($object->getId());
            $object->setData('menu_id', $menuIds);
        }
        return parent::_afterLoad($object);
    }

    protected function loadMenuByItem($itemId, $tableName = 'menumanagement_menu_item', $field = 'menu_id')
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where('item_id = ?', (int) $itemId );
        return $adapter->fetchCol($select);
    }
}
