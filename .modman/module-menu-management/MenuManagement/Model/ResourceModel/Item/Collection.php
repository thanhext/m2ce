<?php
namespace NVT\MenuManagement\Model\ResourceModel\Item;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('NVT\MenuManagement\Model\Item','NVT\MenuManagement\Model\ResourceModel\Item');
        $this->_map['fields']['item_id'] = 'main_table.item_id';
        $this->_map['fields']['store'] ='store_table.store_id';
    }

    /**
     *
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();

        $connection =$this->getResource()->getConnection();
        foreach ($this->_items as $item) {
            try {
                $slect = $connection->select()->from(
                    ['b' => $connection->getTableName('menumanagement_menu_item')],
                    ['menu_id']
                )->where('b.item_id=?', $item->getId());
                $from = $connection->fetchCol($slect);
                $item->setData('menu_id', $from);
            } catch(\Exception $e) {
                $connection->rollBack();
            }
            if ($this->_resetItemsDataChanged && ($item instanceof \Magento\Framework\Model\AbstractModel)) {
                $item->setDataChanges(false);
            }
        }
        $this->_eventManager->dispatch('core_collection_abstract_load_after', ['collection' => $this]);
        if ($this->_eventPrefix && $this->_eventObject) {
            $this->_eventManager->dispatch('item_menu_collection_load_after', [$this->_eventObject => $this]);
        }
        return $this;
    }


    public function addMenuFilter(\NVT\MenuManagement\Model\Menu $menu)
    {
        $this->_itemLimitationFilters['menu_id'] = $menu->getId();
        $this->_applyItemLimitations();
        return $this;
    }

    protected function _applyItemLimitations()
    {
        $filters = $this->_itemLimitationFilters;
        if (!isset($filters['menu_id'])) {
            return $this;
        }
        $conditions = [
            'menu_item.item_id=main_table.item_id',
            $this->getConnection()->quoteInto('menu_item.menu_id=?', $filters['menu_id']),
        ];
        $joinCond = join(' AND ', $conditions);
        $this->getSelect()->join(
            ['menu_item'=>$this->getTable('menumanagement_menu_item')],
            $joinCond,
            ['menu_item'=>'menu_id']
        );
        return $this;
    }
}
