<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Ui\Component\Form;

use Amasty\Blog\Api\Data\CategoryInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Amasty\Blog\Model\ResourceModel\Categories\CollectionFactory as CategoriesCollectionFactory;
use Amasty\Blog\Controller\Adminhtml\Categories\Edit as EditController;

/**
 * Class
 */
class Categories implements OptionSourceInterface
{
    const TREE_ROOT_LABEL = 'Root Category';

    /**
     * @var CategoriesCollectionFactory
     */
    private $categoriesCollectionFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var array
     */
    private $categoriesTree;

    public function __construct(
        CategoriesCollectionFactory $categoriesCollectionFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->categoriesCollectionFactory = $categoriesCollectionFactory;
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getCategoriesTree();
    }

    /**
     * Retrieve categories tree
     *
     * @param bool $displayRoot
     * @return array
     */
    public function getCategoriesTree($displayRoot = false)
    {
        if ($this->categoriesTree === null) {
            $collection = $this->categoriesCollectionFactory->create();

            if ($category = $this->registry->registry(EditController::CURRENT_AMASTY_BLOG_CATEGORY)) {
                $excludePath = $category->getPath() . '/' . $category->getCategoryId();
                $collection
                    ->getSelect()
                    ->where('main_table.path NOT LIKE "?%"', $excludePath);
                $collection->addFieldToFilter('category_id', ['neq' => $category->getCategoryId()]);
            }

            $categoryById = [
                CategoryInterface::ROOT_CATEGORY_ID => [
                    'value' => CategoryInterface::ROOT_CATEGORY_ID,
                    'label' => __('Root Category'),
                    'is_active' => true
                ],
            ];

            /**
             * @var CategoryInterface $category
             */
            foreach ($collection as $category) {
                foreach ([$category->getCategoryId(), (int)$category->getParentId()] as $categoryId) {
                    if (!isset($categoryById[$categoryId])) {
                        $categoryById[$categoryId] = ['value' => (string)$categoryId];
                    }
                }

                $categoryById[$category->getId()]['is_active'] = $category->getStatus();
                $categoryById[$category->getId()]['label'] = $category->getName();
                $categoryById[(int)$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
            }

            if ($displayRoot) {
                $this->categoriesTree = [$categoryById[CategoryInterface::ROOT_CATEGORY_ID]];
            } else {
                $this->categoriesTree = isset($categoryById[CategoryInterface::ROOT_CATEGORY_ID]['optgroup'])
                    ? $categoryById[CategoryInterface::ROOT_CATEGORY_ID]['optgroup']
                    : [];
            }
        }

        return $this->categoriesTree;
    }

    /**
     * @return CategoriesCollectionFactory
     */
    public function getCategoriesCollectionFactory()
    {
        return $this->categoriesCollectionFactory;
    }
}
