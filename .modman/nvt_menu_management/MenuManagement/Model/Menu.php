<?php
namespace NVT\MenuManagement\Model;
class Menu extends \Magento\Framework\Model\AbstractModel implements \NVT\MenuManagement\Api\Data\MenuInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'menu';

    protected function _construct()
    {
        $this->_init('NVT\MenuManagement\Model\ResourceModel\Menu');
        $this->getResource()->getTable('menumanagement_menu_item');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    
    protected   $_itemCollectionFactory;

    /**
     * Menu constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\Item\CollectionFactory $itemCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \NVT\MenuManagement\Model\ResourceModel\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_itemCollectionFactory = $itemCollectionFactory;
        return parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return $this
     */
    public function getItems() {
        return $this->_itemCollectionFactory
                    ->create()->addMenuFilter(
                        $this
                    );
    }
}
