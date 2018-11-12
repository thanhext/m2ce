<?php
namespace AstralWeb\Banner\Ui\Component\Listing\DataProviders\Banner;

class Grid extends \Magento\Ui\DataProvider\AbstractDataProvider
{    
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \AstralWeb\Banner\Model\ResourceModel\Banner\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
