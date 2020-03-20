<?php
namespace NVT\MenuManagement\Ui\Component\Listing\DataProviders\Menumanager;

class Menu extends \Magento\Ui\DataProvider\AbstractDataProvider
{    
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \NVT\MenuManagement\Model\ResourceModel\Menu\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
