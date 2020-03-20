<?php
namespace NVT\MenuManagement\Model;
class ItemMenu extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = 'entity';

    protected function _construct()
    {
        $this->_init('NVT\MenuManagement\Model\ResourceModel\ItemMenu');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
