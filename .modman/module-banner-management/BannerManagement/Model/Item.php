<?php
namespace NVT\BannerManagement\Model;
class Item extends \Magento\Framework\Model\AbstractModel implements \NVT\BannerManagement\Api\Data\ItemInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'item';
    /**
     * @var ItemBannerFactory
     */
    protected $itemBannerFactory;

    public function __construct(
        \NVT\BannerManagement\Model\ItemBannerFactory $itemBannerFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->itemBannerFactory = $itemBannerFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\ResourceModel\Item');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     *
     */

    public function afterSave()
    {
        if(count($this->getBannerId())) {
            $itemId = $this->getItemId();
            $bannerIds = $this->getBannerId();
            $model = $this->itemBannerFactory->create();
            $collection = $model->getCollection();
            $collection->addFieldToFilter('item_id', $this->getItemId());
            //$collection->addFieldToFilter('store_id', $storeId);
            if ($collection->count()) {
                $oldData = [];
                foreach ($collection as $i => $itemBanner) {
                    $oldData[] = $itemBanner->getBannerId();
                    if (!in_array($itemBanner->getBannerId(), $bannerIds)) {
                        $item = $model->load($itemBanner->getEntityId());
                        $item->delete();
                    }
                }
                foreach ($bannerIds as $bannerId) {
                    if(!in_array($bannerId, $oldData)){
                        $data = $this->getData();
                        $model = $this->itemBannerFactory->create();
                        $model->setData($data);
                        $model->setBannerId($bannerId);
                        $model->save();
                    }
                }
            } else {
                foreach ($bannerIds as $bannerId) {
                    $data = $this->getData();
                    $model = $this->itemBannerFactory->create();
                    $model->setData($data);
                    $model->setBannerId($bannerId);
                    $model->save();
                }
            }
        }
        parent::afterSave();
    }
}
