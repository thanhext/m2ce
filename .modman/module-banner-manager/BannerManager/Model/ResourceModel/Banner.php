<?php
namespace T2N\BannerManager\Model\ResourceModel;

use Magento\Framework\DB\Select;
use T2N\BannerManager\Api\Data\BannerInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Banner
 */
class Banner extends AbstractDb
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        $this->_storeManager = $storeManager;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        parent::__construct($context, $connectionName);
    }

    /**
     * Init
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init('banner_entity', 'entity_id');
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(BannerInterface::class)->getEntityConnection();
    }

    /**
     * Perform actions before object save
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $options = $object->getOptions();
        if (is_array($options)) {
            $object->setOptions(json_encode($options));
        }

        if (!$this->getIsUniqueBannerToStores($object)) {
            throw new LocalizedException(
                __('A banner identifier with the same properties already exists in the selected store.')
            );
        }

        return $this;
    }

    /**
     * Get block id.
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return bool|int|string
     * @throws LocalizedException
     * @throws \Exception
     */
    private function getBannerId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        if (!is_numeric($value) && $field === null) {
            $field = 'identifier';
        } elseif (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }
        $entityId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                   ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                   ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $entityId = count($result) ? $result[0] : false;
        }
        return $entityId;
    }

    /**
     * Load an object
     *
     * @param \T2N\BannerManager\Model\Banner|AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $blockId = $this->getBannerId($object, $value, $field);
        if ($blockId) {
            $this->entityManager->load($object, $blockId);
        }
        return $this;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \T2N\BannerManager\Model\Banner|AbstractModel $object
     * @return Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStoreId(), Store::DEFAULT_STORE_ID];

            $select->join(
                ['bes' => $this->getTable('banner_entity_store')],
                $this->getMainTable() . '.' . $linkField . ' = bes.' . $linkField,
                ['store_id']
            )
                   ->where('is_active = ?', 1)
                   ->where('bes.store_id in (?)', $stores)
                   ->order('store_id DESC')
                   ->limit(1);
        }

        return $select;
    }
    /**
     * Check for unique of identifier of block to selected store(s).
     *
     * @param AbstractModel $object
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueBannerToStores(AbstractModel $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $stores = (array)$object->getData('store_id');
        $isDefaultStore = $this->_storeManager->isSingleStoreMode()
                          || array_search(Store::DEFAULT_STORE_ID, $stores) !== false;

        if (!$isDefaultStore) {
            $stores[] = Store::DEFAULT_STORE_ID;
        }

        $select = $this->getConnection()->select()
                       ->from(['cb' => $this->getMainTable()])
                       ->join(
                           ['bes' => $this->getTable('banner_entity_store')],
                           'cb.' . $linkField . ' = bes.' . $linkField,
                           []
                       )
                       ->where('cb.identifier = ?  ', $object->getData('identifier'));

        if (!$isDefaultStore) {
            $select->where('bes.store_id IN (?)', $stores);
        }

        if ($object->getId()) {
            $select->where('cb.' . $entityMetadata->getIdentifierField() . ' <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
                             ->from(['bes' => $this->getTable('banner_entity_store')], 'store_id')
                             ->join(
                                 ['cb' => $this->getMainTable()],
                                 'bes.' . $linkField . ' = cb.' . $linkField,
                                 []
                             )
                             ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :entity_id');

        return $connection->fetchCol($select, ['entity_id' => (int)$id]);
    }
    /**
     * Save an object.
     *
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
