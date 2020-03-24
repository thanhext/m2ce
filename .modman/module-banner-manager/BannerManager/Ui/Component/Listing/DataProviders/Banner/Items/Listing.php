<?php
namespace T2N\BannerManager\Ui\Component\Listing\DataProviders\Banner\Items;


/**
 * Class Listing
 */
class Listing extends \Magento\Ui\DataProvider\AbstractDataProvider
{    
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \T2N\BannerManager\Model\ResourceModel\Banner\Item\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
