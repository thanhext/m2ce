<?php
namespace AstralWeb\Banner\Model\System\Config;

/**
 * Class Type
 * @package AstralWeb\Banner\Model\System\Config
 */
class Type implements \Magento\Framework\Option\ArrayInterface
{
    const TYPE_SLIDER    = 0;
    const TYPE_BANNER    = 1;

    public function toOptionArray()
    {
        return [['value' => self::TYPE_SLIDER, 'label' => __('slides')], ['value'=> self::TYPE_BANNER, 'label'=>__('Banner')]];
    }

    public function toArray()
    {
        return [ self::TYPE_SLIDER => __('slides'), self::TYPE_BANNER => __('Banner')];
    }
}
