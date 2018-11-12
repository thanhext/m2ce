<?php
namespace AstralWeb\Banner\Model\ResourceModel;
/**
 * Class Banner
 * @package AstralWeb\Banner\Model\ResourceModel
 */
class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('banners','banner_id');
    }
}
