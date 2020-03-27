<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\DataProvider;

use Amasty\Blog\Model\Author;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class AuthorDataProvider
 */
class AuthorDataProvider extends AbstractDataProvider
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Amasty\Blog\Api\AuthorRepositoryInterface
     */
    private $authorRepository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        \Amasty\Blog\Api\AuthorRepositoryInterface $authorRepository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->dataPersistor = $dataPersistor;
        $this->authorRepository = $authorRepository;
        $this->collection = $authorRepository->getAuthorCollection();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();

        if ($data['totalRecords'] > 0) {
            $authorId = (int)$data['items'][0]['author_id'];
            $model = $this->authorRepository->getById($authorId);
            $data[$authorId] = $model->getData();
        }

        if ($savedData = $this->dataPersistor->get(Author::PERSISTENT_NAME)) {
            $savedAuthorId = isset($savedData['author_id']) ? $savedData['author_id'] : null;
            $data[$savedAuthorId] = isset($data[$savedAuthorId])
                ? array_merge($data[$savedAuthorId], $savedData)
                : $savedData;
            $this->dataPersistor->clear(Author::PERSISTENT_NAME);
        }

        return $data;
    }
}
