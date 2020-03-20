<?php
namespace NVT\BannerManagement\Ui\Component\Listing\DataProviders\Bannermanager;

class Item extends \Magento\Ui\DataProvider\AbstractDataProvider
{    
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \NVT\BannerManagement\Model\ResourceModel\Item\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
