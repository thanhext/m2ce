<?php
namespace T2N\BannerManager\Ui\Component\Listing\DataProviders;

/**
 * Class Banner
 *
 * @package T2N\BannerManager\Ui\Component\Listing\DataProviders\Banners
 */
class Banner extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \T2N\BannerManager\Model\ResourceModel\Banner\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
