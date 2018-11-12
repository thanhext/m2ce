<?php
namespace AstralWeb\EzShipping\Model\ResourceModel;
class Ez extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('shipping_ez','ez_id');
    }
}
