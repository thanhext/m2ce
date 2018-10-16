<?php
namespace NVT\BannerManagement\Model\ResourceModel\ItemBanner;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\ItemBanner','NVT\BannerManagement\Model\ResourceModel\ItemBanner');
    }
}
