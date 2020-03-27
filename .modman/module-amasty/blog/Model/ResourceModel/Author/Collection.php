<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\ResourceModel\Author;

use Amasty\Blog\Model\ResourceModel\Traits\CollectionTrait;
use Magento\Store\Model\Store;

/**
 * Class
 */
class Collection extends \Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection
{
    use CollectionTrait;

    /**
     * @var string
     */
    protected $_idFieldName = 'author_id';

    /**
     * @var array
     */
    protected $_map = [
        'fields' => [
            'author_id' => 'main_table.author_id'
        ]
    ];

    /**
     * @var string
     */
    private $queryText;

    public function _construct()
    {
        $this->_init(\Amasty\Blog\Model\Author::class, \Amasty\Blog\Model\ResourceModel\Author::class);
    }

    /**
     * @return string
     */
    public function getQueryText()
    {
        return $this->queryText;
    }

    /**
     * @param string $queryText
     */
    public function setQueryText(string $queryText)
    {
        $this->queryText = $queryText;
    }
}
