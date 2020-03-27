<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Ui\Component\Listing;

/**
 * Class Categories
 */
class Categories extends \Amasty\Blog\Ui\Component\Form\Categories
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $options = [];
        $collection = $this->getCategoriesCollectionFactory()->create();
        foreach ($collection as $category) {
            $options[] = [
                'value' => $category->getCategoryId(),
                'label' => $category->getName()
            ];
        }

        return $options;
    }
}
