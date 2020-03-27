<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\ResourceModel\Categories;

/**
 * Class
 */
class Collection extends \Amasty\Blog\Model\ResourceModel\Abstracts\Collection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'category_id';

    /**
     * @var array
     */
    protected $_map = [
        'fields' => [
            'category_id' => 'main_table.category_id'
        ]
    ];

    public function _construct()
    {
        $this->_init(
            \Amasty\Blog\Model\Categories::class,
            \Amasty\Blog\Model\ResourceModel\Categories::class
        );
    }

    /**
     * @return $this
     */
    public function addStores()
    {
        $this->getSelect()
            ->joinLeft(
                ['store' => $this->getTable('amasty_blog_categories_store')],
                'main_table.category_id = store.category_id',
                []
            );

        return $this;
    }

    /**
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this|\Amasty\Blog\Model\ResourceModel\Abstracts\Collection
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            parent::load($printQuery, $logQuery);
            $this->addLinkedData('store', 'store_id', 'store_id');
        }

        return $this;
    }

    /**
     * @param $linkedTable
     * @param $linkedField
     * @param $fieldName
     */
    private function addLinkedData($linkedTable, $linkedField, $fieldName)
    {
        $connection = $this->getConnection();

        $postId = $this->getColumnValues('category_id');
        $linked = [];
        if (count($postId) > 0) {
            $inCond = $connection->prepareSqlCondition('category_id', ['in' => $postId]);
            $select = $connection->select()
                ->from($this->getTable('amasty_blog_categories_' . $linkedTable))
                ->where($inCond);
            $result = $connection->fetchAssoc($select);
            foreach ($result as $row) {
                if (!isset($linked[$row['category_id']])) {
                    $linked[$row['category_id']] = [];
                }
                $linked[$row['category_id']][] = $row[$linkedField];
            }
        }

        foreach ($this as $item) {
            if (isset($linked[$item->getId()])) {
                $item->setData($fieldName, $linked[$item->getId()]);
            } else {
                $item->setData($fieldName, []);
            }
        }
    }

    /**
     * @param $direction
     * @return $this
     */
    public function setSortOrder($direction)
    {
        $this->getSelect()->order("main_table.sort_order {$direction}");

        return $this;
    }

    /**
     * @param $postId
     *
     * @return $this
     */
    public function addPostFilter($postId)
    {
        $postTable = $this->getTable('amasty_blog_posts_category');

        $this->getSelect()
            ->join(['post' => $postTable], "post.category_id = main_table.category_id", [])
            ->where("post.post_id = ?", $postId);

        return $this;
    }

    /**
     * @param array $categoryIds
     * @return $this
     */
    public function addIdFilter($categoryIds = [])
    {
        if (!is_array($categoryIds)) {
            $categoryIds = [$categoryIds];
        }
        $this->addFieldToFilter('category_id', ['in' => $categoryIds]);

        return $this;
    }

    /**
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        parent::renderFilters();
        if ($this->getQueryText()) {
            $this->getSelect()->group('main_table.category_id');
        }
    }

    /**
     * @param $limit
     */
    public function setLimit($limit)
    {
        $this->getSelect()->limit($limit);
    }

    /**
     * @param array $stores
     *
     * @return array
     */
    public function getPostsCount($stores)
    {
        $select = $this->getConnection()->select()
            ->from(
                ['posts_cat' => $this->getTable('amasty_blog_posts_category')],
                ['category' => 'posts_cat.category_id', 'posts_count' => 'COUNT(posts_cat.category_id)']
            )->join(
                ['posts' => $this->getTable('amasty_blog_posts')],
                'posts.post_id = posts_cat.post_id AND posts.status = 2',
                []
            )->join(
                ['posts_store' => $this->getTable('amasty_blog_posts_store')],
                'posts_store.post_id = posts.post_id',
                []
            )->where(
                'posts_store.store_id IN (?)',
                $stores
            )
            ->group('category');

        return $this->getConnection()->fetchPairs($select);
    }
}
