<?php
namespace T2N\BannerManager\Model\Banner;
use Magento\Framework\Session\SessionManagerInterface;
use T2N\BannerManager\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    private $loadedData = [];

    /**
     * @var SessionManagerInterface
     */
    private $session;

    /**
     * @var
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

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
        SessionManagerInterface $session,
        array $meta = [],
        array $data = []
    ) {
        $this->session = $session;
        $this->dataPersistor = $dataPersistor;
        $this->collection = $collectionFactory->create();
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
    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        $banners = $this->collection->getItems();
        /** @var \T2N\BannerManager\Model\Banner $item */
        foreach ($banners as $banner) {
            $data = $this->_prepareData($banner, ['options', 'banner_items']);
            $result['banner'] = $data;
            $result['options'] = $data['options'];
            unset($data['options']);
            $result['banner_id'] = $banner->getId();
            $this->loadedData[$banner->getId()] = $result;
        }
        //$data = $this->session->getBannerFormData();
        $data = $this->dataPersistor->get('banner_entity');
        if (!empty($data)) {
            $banner = $this->collection->getNewEmptyItem();
            $banner->setData($data);
            $this->loadedData[$banner->getId()] = $banner->getData();
            $this->dataPersistor->clear('banner_entity');
            //$this->session->unsBannerFormData();
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
