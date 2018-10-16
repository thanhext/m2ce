<?php
namespace NVT\BannerManagement\Model\System\Config;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    const TYPE_SLIDER    = 1;
    const TYPE_BANNER    = 2;

    public function toOptionArray()
    {
        return [['value' => self::TYPE_SLIDER, 'label' => __('Slider')], ['value'=> self::TYPE_BANNER, 'label'=>__('Banner')]];
    }

    public function toArray()
    {
        return [ self::TYPE_SLIDER => __('Slider'), self::TYPE_BANNER => __('Banner')];
    }
}
