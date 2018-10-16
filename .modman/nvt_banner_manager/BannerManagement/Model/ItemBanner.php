<?php
namespace NVT\BannerManagement\Model;
class ItemBanner extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = 'entity';

    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\ResourceModel\ItemBanner');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
