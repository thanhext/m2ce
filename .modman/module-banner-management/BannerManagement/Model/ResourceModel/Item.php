<?php
namespace NVT\BannerManagement\Model\ResourceModel;
class Item extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('bannermanagement_item','item_id');
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
            $bannerIds = $this->loadBannerByItem($object->getId());
            $object->setData('banner_id', $bannerIds);
        }
        return parent::_afterLoad($object);
    }

    protected function loadBannerByItem($itemId, $tableName = 'bannermanagement_banner_item', $field = 'banner_id')
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where('item_id = ?', (int) $itemId );
        return $adapter->fetchCol($select);
    }
}
