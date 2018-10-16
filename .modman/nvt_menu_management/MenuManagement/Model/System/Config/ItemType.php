<?php
namespace NVT\MenuManagement\Model\System\Config;

class ItemType implements \Magento\Framework\Option\ArrayInterface
{
    const ITEM_TYPE_BRAND         = 1;
    const ITEM_TYPE_CATEGORY      = 2;
    const ITEM_TYPE_CMS_PAGE      = 3;
    const ITEM_TYPE_CUSTOM_URL    = 4;

    public function toOptionArray()
    {
        return [
            ['value'=> self::ITEM_TYPE_CATEGORY, 'label'=>__('Category')],
            ['value' => self::ITEM_TYPE_BRAND, 'label' => __('Brand')],
            ['value' => self::ITEM_TYPE_CMS_PAGE, 'label' => __('CMS Page')],
            ['value' => self::ITEM_TYPE_CUSTOM_URL, 'label' => __('Custom URL')]

        ];
    }

    public function toArray()
    {
        return [
            self::ITEM_TYPE_CATEGORY => __('Category'),
            self::ITEM_TYPE_BRAND => __('Brand'),
            self::ITEM_TYPE_CMS_PAGE => __('CMS Page'),
            self::ITEM_TYPE_CUSTOM_URL => __('Custom URL')
        ];
    }
}
