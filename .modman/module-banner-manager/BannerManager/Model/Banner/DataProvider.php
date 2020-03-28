<?php
namespace T2N\BannerManager\Model\Banner;
use T2N\BannerManager\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

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
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
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
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $banners = $this->collection->getItems();
        /** @var \T2N\BannerManager\Model\Banner $item */
        foreach ($banners as $banner) {
            $data = $this->_prepareData($banner, ['options', 'banner_items']);
            $this->loadedData[$banner->getId()] = $data;
        }

        $data = $this->dataPersistor->get('banner_entity');
        if (!empty($data)) {
            $banner = $this->collection->getNewEmptyItem();
            $banner->setData($data);
            $this->loadedData[$banner->getId()] = $banner->getData();
            $this->dataPersistor->clear('banner_entity');
        }

        return $this->loadedData;
    }

    /**
     * @param       $banner
     * @param array $fields
     *
     * @return mixed
     */
    private function _prepareData($banner, $fields = [])
    {
        foreach ($fields as $field) {
            $result = $banner->getData($field);
            if (is_string($result)) {
                $banner->setData($field, json_decode($result, true));
            }
        }

        return $banner->getData();
    }

}
