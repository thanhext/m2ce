<?php
namespace NVT\MenuManagement\Model\System\Config;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLE    = 2;

    public function toOptionArray()
    {
        return [['value' => self::STATUS_DISABLE, 'label' => __('Disable')], ['value'=> self::STATUS_ENABLED, 'label'=>__('Enable')]];
    }

    public function toArray()
    {
        return [ self::STATUS_DISABLE => __('Disable'), self::STATUS_ENABLED => __('Enable')];
    }
}
