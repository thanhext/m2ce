<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\DataProvider;

use Amasty\Blog\Model\Categories;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Amasty\Blog\Model\ResourceModel\Categories\CollectionFactory;

/**
 * Class
 */
class CategoryDataProvider extends AbstractDataProvider
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Amasty\Blog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        \Amasty\Blog\Api\CategoryRepositoryInterface $categoryRepository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();

        if ($data['totalRecords'] > 0) {
            $categoryId = (int)$data['items'][0]['category_id'];
            $model = $this->categoryRepository->getById($categoryId);
            $data[$categoryId] = $model->getData();
        }

        if ($savedData = $this->dataPersistor->get(Categories::PERSISTENT_NAME)) {
            $savedCategoryId = isset($savedData['category_id']) ? $savedData['category_id'] : null;
            $data[$savedCategoryId] = isset($data[$savedCategoryId])
                ? array_merge($data[$savedCategoryId], $savedData)
                : $savedData;
            $this->dataPersistor->clear(Categories::PERSISTENT_NAME);
        }

        return $data;
    }
}
