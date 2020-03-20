<?php
namespace NVT\BannerManagement\Model\ResourceModel;
class ItemBanner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('bannermanagement_banner_item','entity_id');
    }
}
