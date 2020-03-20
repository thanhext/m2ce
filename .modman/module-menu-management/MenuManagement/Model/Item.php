<?php
namespace NVT\MenuManagement\Model;
class Item extends \Magento\Framework\Model\AbstractModel implements \NVT\MenuManagement\Api\Data\ItemInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'item';

    protected function _construct()
    {
        $this->_init('NVT\MenuManagement\Model\ResourceModel\Item');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @var ItemMenuFactory
     */
    protected $itemMenuFactory;

    /**
     * Item constructor.
     * @param ItemMenuFactory $itemMenuFactory
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \NVT\MenuManagement\Model\ItemMenuFactory $itemMenuFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->itemMenuFactory = $itemMenuFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * 
     */
    public function afterSave()
    {
        if(count($this->getMenuId())) {
            $itemId = $this->getItemId();
            $menuIds = $this->getMenuId();
            $model = $this->itemMenuFactory->create();
            $collection = $model->getCollection();
            $collection->addFieldToFilter('item_id', $this->getItemId());
            //$collection->addFieldToFilter('store_id', $storeId);
            if ($collection->count()) {
                $oldData = [];
                foreach ($collection as $i => $itemMenu) {
                    $oldData[] = $itemMenu->getMenuId();
                    if (!in_array($itemMenu->getMenuId(), $menuIds)) {
                        $item = $model->load($itemMenu->getEntityId());
                        $item->delete();
                    }
                }
                foreach ($menuIds as $menuId) {
                    if(!in_array($menuId, $oldData)){
                        $data = $this->getData();
                        $model = $this->itemMenuFactory->create();
                        $model->setData($data);
                        $model->setMenuId($menuId);
                        $model->save();
                    }
                }
            } elseif (is_array($menuIds)) {
                foreach ($menuIds as $menuId) {
                    $data = $this->getData();
                    $model = $this->itemMenuFactory->create();
                    $model->setData($data);
                    $model->setMenuId($menuId);
                    $model->save();
                }
            }
        }
        parent::afterSave();
    }
}
