<?php
namespace Ecommage\Blog\Ui\Component\Listing\DataProviders\Blog;
use Ecommage\Blog\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class Listing
 * @package Ecommage\Blog\Ui\Component\Listing\DataProviders\Blog
 */
class Listing extends \Magento\Ui\DataProvider\AbstractDataProvider
{    
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
