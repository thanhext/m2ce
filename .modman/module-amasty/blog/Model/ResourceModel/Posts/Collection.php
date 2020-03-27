<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\ResourceModel\Posts;

use Amasty\Blog\Api\Data\PostInterface;

/**
 * Class
 */
class Collection extends \Amasty\Blog\Model\ResourceModel\Abstracts\Collection
{
    /**
     * @var array
     */
    protected $_map = [
        'fields' => [
            'post_id' => 'main_table.post_id'
        ]
    ];

    /**
     * @var string
     */
    protected $_idFieldName = 'post_id';

    /**
     * @var \Amasty\Blog\Model\ResourceModel\Author\CollectionFactory
     */
    private $authorCollectionFactory;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Amasty\Blog\Model\ResourceModel\Author\CollectionFactory $authorCollectionFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->authorCollectionFactory = $authorCollectionFactory;
    }

    public function _construct()
    {
        $this->_init(\Amasty\Blog\Model\Posts::class, \Amasty\Blog\Model\ResourceModel\Posts::class);
    }

    /**
     * @return $this
     */
    public function addStores()
    {
        $this->getSelect()
            ->joinLeft(
                ['store' => $this->getTable('amasty_blog_posts_store')],
                'main_table.post_id = store.post_id',
                []
            );

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCategories()
    {
        $this->getSelect()
            ->joinLeft(
                ['categories' => $this->getTable('amasty_blog_posts_category')],
                'main_table.post_id = categories.post_id',
                []
            );

        return $this;
    }

    /**
     * @param $tagId
     *
     * @return $this
     */
    public function addTagFilter($tagIds)
    {
        if (!is_array($tagIds)) {
            $tagIds = [$tagIds];
        }
        $this->getSelect()
            ->joinLeft(
                ['tags' => $this->getTable('amasty_blog_posts_tag')],
                'main_table.post_id = tags.post_id',
                []
            )
            ->where('tags.tag_id IN (?)', $tagIds)
            ->group('main_table.post_id');

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function loadByQueryText($value)
    {
        $this->getSelect()
            ->where('main_table.full_content LIKE ?', '%' . $value . '%')
            ->orWhere('main_table.title LIKE ?', '%' . $value . '%');

        return $this;
    }

    /**
     * Load data
     *
     * @param boolean $printQuery
     * @param boolean $logQuery
     *
     * @return $this
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            parent::load($printQuery, $logQuery);
            $this->addLinkedTables();
            $this->loadAuthors();
        }

        return $this;
    }

    /**
     * @return void
     */
    private function addLinkedTables()
    {
        $this->addLinkedData('category', 'category_id', 'categories');
        $this->addLinkedData('store', 'store_id', 'store_id');
        $this->addLinkedData('tag', 'tag_id', 'tag_ids');
    }

    /**
     * @param $linkedTable
     * @param $linkedField
     * @param $fieldName
     */
    private function addLinkedData($linkedTable, $linkedField, $fieldName)
    {
        $connection = $this->getConnection();

        $postId = $this->getColumnValues('post_id');
        $linked = [];
        if (!empty($postId)) {
            $inCond = $connection->prepareSqlCondition('post_id', ['in' => $postId]);
            $select = $connection->select()
                ->from($this->getTable('amasty_blog_posts_' . $linkedTable))->where($inCond);
            $result = $connection->fetchAll($select);
            foreach ($result as $row) {
                if (!isset($linked[$row['post_id']])) {
                    $linked[$row['post_id']] = [];
                }
                $linked[$row['post_id']][] = $row[$linkedField];
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
     * @return $this
     */
    private function loadAuthors()
    {
        $authorIds = [];
        /**
         * @var PostInterface $post
         */
        foreach ($this->getItems() as $post) {
            $authorIds[] = $post->getAuthorId();
        }

        $collection = $this->authorCollectionFactory->create();
        $collection->addFieldToFilter(PostInterface::AUTHOR_ID, ['in' => $authorIds]);

        foreach ($this->getItems() as $post) {
            if (!$author = $collection->getItemById($post->getAuthorId())) {
                $author = $collection->getNewEmptyItem();
            }
            $post->setAuthor($author);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function setUrlKeyIsNotNull()
    {
        $this->getSelect()->where('main_table.url_key != ""');

        return $this;
    }

    /**
     * @return $this
     */
    public function setDateOrder()
    {
        $this->getSelect()->order('IFNULL(main_table.published_at, main_table.created_at) DESC');

        return $this;
    }

    /**
     * @param $categoryIds
     *
     * @return $this
     */
    public function addCategoryFilter($categoryIds)
    {
        $categoryIds = is_array($categoryIds) ? $categoryIds : [$categoryIds];

        $categoryTable = $this->getMainTable() . "_category";
        $this->getSelect()
            ->join(['categories' => $categoryTable], 'categories.post_id = main_table.post_id', [])
            ->where('categories.category_id IN (?)', $categoryIds);

        return $this;
    }

    /**
     * @param $authorIds
     *
     * @return $this
     */
    public function addAuthorFilter($authorIds)
    {
        $authorIds = is_array($authorIds) ? $authorIds : [$authorIds];

        $this->getSelect()
            ->join(['author' => $this->getTable('amasty_blog_author')], 'author.author_id = main_table.author_id', [])
            ->where('author.author_id IN (?)', $authorIds);

        return $this;
    }

    protected function _renderFiltersBefore()
    {
        parent::renderFilters();
        if ($this->getQueryText()) {
            $this->getSelect()->group('main_table.post_id');
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
     * @return \Magento\Framework\DB\Select
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSelectCountSql()
    {
        $this->applyStoreFilter();
        return parent::getSelectCountSql();
    }
}
