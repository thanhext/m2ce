<?php
namespace AstralWeb\EzShipping\Model;
class Ez extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'shipping_ez';

    protected function _construct()
    {
        $this->_init('AstralWeb\EzShipping\Model\ResourceModel\Ez');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
