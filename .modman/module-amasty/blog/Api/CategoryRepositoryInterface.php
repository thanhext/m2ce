<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Api;

use Amasty\Blog\Model\ResourceModel\Categories\Collection;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @api
 */
interface CategoryRepositoryInterface
{
    /**
     * Save
     *
     * @param \Amasty\Blog\Api\Data\CategoryInterface $category
     *
     * @return \Amasty\Blog\Api\Data\CategoryInterface
     */
    public function save(\Amasty\Blog\Api\Data\CategoryInterface $category);

    /**
     * Get by id
     *
     * @param int $categoryId
     *
     * @return \Amasty\Blog\Api\Data\CategoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($categoryId);

    /**
     * @param $urlKey
     * @return \Amasty\Blog\Model\Categories
     */
    public function getByUrlKey($urlKey);

    /**
     * @return \Amasty\Blog\Model\Categories
     */
    public function getCategory();

    /**
     * Delete
     *
     * @param \Amasty\Blog\Api\Data\CategoryInterface $category
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Amasty\Blog\Api\Data\CategoryInterface $category);

    /**
     * Delete by id
     *
     * @param int $categoryId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($categoryId);

    /**
     * Lists
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @return array
     */
    public function getAllCategories();

    /**
     * @param $categoryId
     *
     * @return array
     */
    public function getStores($categoryId);

    /**
     * @param $postId
     * @return Collection
     */
    public function getCategoriesByPost($postId);

    /**
     * @param null $storeId
     * @return Collection
     */
    public function getActiveCategories($storeId = null);

    /**
     * @param array $categoryIds
     * @return Collection
     */
    public function getCategoriesByIds($categoryIds = []);

    /**
     * @param $parentId
     * @param $limit
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getChildrenCategories($parentId, $limit);
}
