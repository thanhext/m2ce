<?php
namespace AstralWeb\Banner\Model\System\Config;

/**
 * Class MediaType
 * @package AstralWeb\Banner\Model\System\Config
 */
class MediaType implements \Magento\Framework\Option\ArrayInterface
{
    const TYPE_IMAGE    = 0;
    const TYPE_VIDEO    = 1;

    public function toOptionArray()
    {
        return [['value' => self::TYPE_IMAGE, 'label' => __('Image')], ['value'=> self::TYPE_VIDEO, 'label'=>__('Video')]];
    }

    public function toArray()
    {
        return [ self::TYPE_IMAGE => __('Image'), self::TYPE_VIDEO => __('Video')];
    }
}
