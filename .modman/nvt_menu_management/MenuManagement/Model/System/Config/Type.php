<?php
namespace NVT\MenuManagement\Model\System\Config;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    const TYPE_VERTICAL     = 2;
    const TYPE_HORIZONTAL   = 1;

    public function toOptionArray()
    {
        return [['value' => self::TYPE_VERTICAL, 'label' => __('Vertical')], ['value'=> self::TYPE_HORIZONTAL, 'label'=>__('Horizontal')]];
    }

    public function toArray()
    {
        return [ self::TYPE_VERTICAL => __('Vertical'), self::TYPE_HORIZONTAL => __('Horizontal')];
    }
}
