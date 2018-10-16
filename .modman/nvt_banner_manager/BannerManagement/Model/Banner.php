<?php
namespace NVT\BannerManagement\Model;
class Banner extends \Magento\Framework\Model\AbstractModel implements \NVT\BannerManagement\Api\Data\BannerInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG         = 'banner';

    protected   $_itemCollectionFactory;
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \NVT\BannerManagement\Model\ResourceModel\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_itemCollectionFactory = $itemCollectionFactory;
        return parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\ResourceModel\Banner');
        $this->_initTables();
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    protected function _initTables()
    {
        $this->_itemBannerTable = $this->getResource()->getTable('bannermanagement_banner_item');
    }
    /**
     * Get banner items collection
     *
     * @return \Magento\Framework\Data\Collection\AbstractDb
     */
    public function getBannerItemsCollection()
    {
        $collection = $this->_itemCollectionFactory->create()->addBannerFilter(
            $this
        );
        return $collection;
    }
//
//    protected function loadBannerByItem($itemId, $tableName = 'bannermanagement_banner_item', $field = 'banner_id')
//    {
//        $adapter = $this->getConnection();
//        $select = $adapter->select()->from(
//            $this->getTable($tableName),
//            $field
//        )->where('item_id = ?', (int) $itemId );
//        return $adapter->fetchCol($select);
//    }
}

