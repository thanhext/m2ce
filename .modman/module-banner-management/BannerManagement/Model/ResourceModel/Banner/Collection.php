<?php
namespace NVT\BannerManagement\Model\ResourceModel\Banner;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('NVT\BannerManagement\Model\Banner','NVT\BannerManagement\Model\ResourceModel\Banner');
    }
}
