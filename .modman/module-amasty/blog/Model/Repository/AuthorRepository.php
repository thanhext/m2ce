<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\Repository;

use Amasty\Blog\Api\Data\AuthorInterface;
use Amasty\Blog\Api\AuthorRepositoryInterface;
use Amasty\Blog\Model\Source\PostStatus;
use Amasty\Blog\Model\AuthorFactory;
use Amasty\Blog\Model\ResourceModel\Author as AuthorResource;
use Amasty\Blog\Model\ResourceModel\Author\CollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AuthorRepository implements AuthorRepositoryInterface
{
    /**
     * @var AuthorFactory
     */
    private $authorFactory;

    /**
     * @var AuthorResource
     */
    private $authorResource;

    /**
     * Model data storage
     *
     * @var array
     */
    private $authors;

    /**
     * @var CollectionFactory
     */
    private $authorCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;

    public function __construct(
        AuthorFactory $authorFactory,
        AuthorResource $authorResource,
        CollectionFactory $authorCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface
    ) {
        $this->authorFactory = $authorFactory;
        $this->authorResource = $authorResource;
        $this->authorCollectionFactory = $authorCollectionFactory;
        $this->storeManagerInterface = $storeManagerInterface;
    }

    /**
     * @param AuthorInterface $author
     *
     * @return AuthorInterface
     * @throws CouldNotSaveException
     */
    public function save(AuthorInterface $author)
    {
        try {
            if ($author->getAuthorId()) {
                $author = $this->getById($author->getAuthorId())->addData($author->getData());
            } else {
                $author->setAuthorId(null);
            }
            $this->authorResource->save($author);
            unset($this->authors[$author->getAuthorId()]);
        } catch (\Exception $e) {
            if ($author->getAuthorId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save author with ID %1. Error: %2',
                        [$author->getAuthorId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new author. Error: %1', $e->getMessage()));
        }

        return $author;
    }

    /**
     * @param int $authorId
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($authorId)
    {
        if (!isset($this->authors[$authorId])) {
            /** @var \Amasty\Blog\Model\Author $author */
            $author = $this->authorFactory->create();
            $this->authorResource->load($author, $authorId);
            /**
             * @TODO add NoSuchEntityException for Author management
             */
            $this->authors[$authorId] = $author;
        }

        return $this->authors[$authorId];
    }

    /**
     * @param $urlKey
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function getByName($name)
    {
        $author = $this->authorFactory->create();
        $this->authorResource->load($author, $name, AuthorInterface::NAME);

        return $author;
    }

    /**
     * @param $urlKey
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function getByUrlKey($urlKey)
    {
        $author = $this->authorFactory->create();
        $this->authorResource->load($author, $urlKey, AuthorInterface::URL_KEY);

        return $author;
    }

    /**
     * @param AuthorInterface $author
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(AuthorInterface $author)
    {
        try {
            $this->authorResource->delete($author);
            unset($this->authors[$author->getAuthorId()]);
        } catch (\Exception $e) {
            if ($author->getAuthorId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove author with ID %1. Error: %2',
                        [$author->getAuthorId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove author. Error: %1', $e->getMessage()));
        }

        return true;
    }

    /**
     * @param int $authorId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($authorId)
    {
        $authorModel = $this->getById($authorId);
        $this->delete($authorModel);

        return true;
    }

    /**
     * @param string[] $authors
     *
     * @return \Amasty\Blog\Model\ResourceModel\Author\Collection
     */
    public function getList($authors)
    {
        return $this->authorCollectionFactory->create()->addFieldToFilter(AuthorInterface::NAME, ['in' => $authors]);
    }

    /**
     * @return \Amasty\Blog\Model\Author
     */
    public function getAuthorModel()
    {
        return $this->authorFactory->create();
    }

    /**
     * @return AuthorResource\Collection
     */
    public function getAuthorCollection()
    {
        return $this->authorCollectionFactory->create();
    }

    /**
     * @param $postId
     * @return AuthorResource\Collection
     */
    public function getAuthorsByPost($postId)
    {
        $authors = $this->authorCollectionFactory->create();
        $authors->addPostFilter($postId);

        return $authors;
    }

    /**
     * @param null $storeId
     * @return AuthorResource\Collection
     * @throws NoSuchEntityException
     */
    public function getActiveAuthors($storeId = null)
    {
        $authors = $this->authorCollectionFactory->create();
        $store = $this->storeManagerInterface->isSingleStoreMode()
            ? null
            : $this->storeManagerInterface->getStore()->getId();
        $authors->addWeightData($storeId ?: $store);
        $authors->setPostStatusFilter(PostStatus::STATUS_ENABLED);

        return $authors;
    }

    /**
     * @return \Magento\Framework\DataObject[]
     */
    public function getAllAuthors()
    {
        return $this->authorCollectionFactory->create()->getItems();
    }

    /**
     * @param string $name
     * @param string $googleProfile
     * @param string $facebookProfile
     * @param string $twitterProfile
     * @return AuthorInterface
     */
    public function createAuthor($name, $googleProfile, $facebookProfile, $twitterProfile)
    {
        return $this->authorResource->createAuthor($name, $googleProfile, $facebookProfile, $twitterProfile);
    }
}
