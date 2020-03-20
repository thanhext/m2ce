<?php
namespace NVT\BannerManagement\Model\ResourceModel;
class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('bannermanagement_banner','banner_id');
    }
}
