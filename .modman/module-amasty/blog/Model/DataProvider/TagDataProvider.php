<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\DataProvider;

use Amasty\Blog\Model\Tag;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Amasty\Blog\Model\ResourceModel\Tag\CollectionFactory;

/**
 * Class
 */
class TagDataProvider extends AbstractDataProvider
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Amasty\Blog\Api\TagRepositoryInterface
     */
    private $tagRepository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        \Amasty\Blog\Api\TagRepositoryInterface $tagRepository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();

        if ($data['totalRecords'] > 0) {
            $tagId = (int)$data['items'][0]['tag_id'];
            $model = $this->tagRepository->getById($tagId);
            $data[$tagId] = $model->getData();
        }

        if ($savedData = $this->dataPersistor->get(Tag::PERSISTENT_NAME)) {
            $savedTagId = isset($savedData['tag_id']) ? $savedData['tag_id'] : null;
            $data[$savedTagId] = isset($data[$savedTagId])
                ? array_merge($data[$savedTagId], $savedData)
                : $savedData;
            $this->dataPersistor->clear(Tag::PERSISTENT_NAME);
        }

        return $data;
    }
}
