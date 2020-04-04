<?php

namespace T2N\BannerManager\Model\Banner;

use T2N\BannerManager\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use T2N\BannerManager\Model\Banner;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData = [];

    /**
     * @param string                 $name
     * @param string                 $primaryFieldName
     * @param string                 $requestFieldName
     * @param CollectionFactory      $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array                  $meta
     * @param array                  $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection    = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        $banners = $this->collection->getItems();
        /** @var \T2N\BannerManager\Model\Banner $banner */
        foreach ($banners as $banner) {
            $this->loadedData[$banner->getId()] = $this->prepareData($banner);
        }

        $data = $this->dataPersistor->get('banner_entity');
        if (!empty($data)) {
            $this->loadedData[$banner->getId()] = $this->prepareData($data);;
            $this->dataPersistor->clear('banner_entity');
        }

        return $this->loadedData;
    }

    /**
     *  Prepares Data
     * @param $data
     *
     * @return array
     */
    private function prepareData($data): array
    {
        $bannerData = $data;
        if (!($data instanceof \T2N\BannerManager\Model\Banner)) {
            $bannerData = $this->collection->getNewEmptyItem();
            $bannerData->setData($data);
        }

        $result[Banner::FORM_GENERAL] = $bannerData->getData();
        $result[Banner::FORM_OPTIONS] = $bannerData->getOptions();
        return $result;
    }
}
