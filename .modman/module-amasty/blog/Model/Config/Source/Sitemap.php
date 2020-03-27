<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class
 */
class Sitemap implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => \Amasty\Blog\Model\Sitemap::AMBLOG_TYPE_BLOG, 'label' => __('Blog')],
            ['value' => \Amasty\Blog\Model\Sitemap::AMBLOG_TYPE_POST, 'label' => __('Posts')],
            ['value' => \Amasty\Blog\Model\Sitemap::AMBLOG_TYPE_CATEGORY, 'label' => __('Categories')],
            ['value' => \Amasty\Blog\Model\Sitemap::AMBLOG_TYPE_TAG, 'label' => __('Tags')],
        ];
    }
}
