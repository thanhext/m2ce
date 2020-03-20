<?php

namespace T2N\SuggestProducts\Model\Config\Source;

use Magento\Catalog\Helper\Category;
use Magento\Framework\Data\Tree\Node\Collection;
use Magento\Framework\Option\ArrayInterface;

class Categories implements ArrayInterface
{
    /**
     * @var array
     */
    protected $optionArray = [];
    /**
     * @var Category
     */
    protected $_categoryHelper;

    /**
     * Categories constructor.
     *
     * @param Category $catalogCategory
     */
    public function __construct(Category $catalogCategory)
    {
        $this->_categoryHelper = $catalogCategory;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $options    = [];
        $categories = $this->toOptionArray();
        foreach ($categories as $option) {
            list($value, $label) = $option;
            $options[$value] = $label;
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (empty($this->optionArray)) {
            $options = [];
            /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categories */
            $categories = $this->getStoreCategories(true, true, false);
            $categories->addAttributeToSelect(['name']);
            foreach ($categories as $category) {
                $options[] = [
                    'value' => $category->getId(),
                    'label' => __('%1%2', $this->getPrefix($category->getLevel()), $category->getName())
                ];
            }
            $this->optionArray = $options;
        }

        return $this->optionArray;
    }

    protected function getPrefix($level)
    {
        return '|' . str_repeat('-', (int)$level);
    }

    /**
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     *
     * @return Collection
     */
    protected function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted, $asCollection, $toLoad);
    }
}
