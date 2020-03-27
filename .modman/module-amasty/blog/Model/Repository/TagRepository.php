<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\Repository;

use Amasty\Blog\Api\Data\TagInterface;
use Amasty\Blog\Api\TagRepositoryInterface;
use Amasty\Blog\Model\Source\PostStatus;
use Amasty\Blog\Model\TagFactory;
use Amasty\Blog\Model\ResourceModel\Tag as TagResource;
use Amasty\Blog\Model\ResourceModel\Tag\CollectionFactory;
use Amasty\Blog\Model\ResourceModel\Tag\Collection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TagRepository implements TagRepositoryInterface
{
    /**
     * @var TagFactory
     */
    private $tagFactory;

    /**
     * @var TagResource
     */
    private $tagResource;

    /**
     * Model data storage
     *
     * @var array
     */
    private $tags;

    /**
     * @var CollectionFactory
     */
    private $tagCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;

    public function __construct(
        TagFactory $tagFactory,
        TagResource $tagResource,
        CollectionFactory $tagCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface
    ) {
        $this->tagFactory = $tagFactory;
        $this->tagResource = $tagResource;
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->storeManagerInterface = $storeManagerInterface;
    }

    /**
     * @param TagInterface $tag
     *
     * @return TagInterface
     * @throws CouldNotSaveException
     */
    public function save(TagInterface $tag)
    {
        try {
            if ($tag->getTagId()) {
                $tag = $this->getById($tag->getTagId())->addData($tag->getData());
            } else {
                $tag->setTagId(null);
            }
            $this->tagResource->save($tag);
            unset($this->tags[$tag->getTagId()]);
        } catch (\Exception $e) {
            if ($tag->getTagId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save tag with ID %1. Error: %2',
                        [$tag->getTagId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new tag. Error: %1', $e->getMessage()));
        }

        return $tag;
    }

    /**
     * @param int $tagId
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($tagId)
    {
        if (!isset($this->tags[$tagId])) {
            /** @var \Amasty\Blog\Model\Tag $tag */
            $tag = $this->tagFactory->create();
            $this->tagResource->load($tag, $tagId);
            if (!$tag->getTagId()) {
                throw new NoSuchEntityException(__('Tag with specified ID "%1" not found.', $tagId));
            }
            $this->tags[$tagId] = $tag;
        }

        return $this->tags[$tagId];
    }

    /**
     * @param $urlKey
     * @return \Amasty\Blog\Api\Data\TagInterface
     */
    public function getByUrlKey($urlKey)
    {
        $tag = $this->tagFactory->create();
        $this->tagResource->load($tag, $urlKey, 'url_key');

        return $tag;
    }

    /**
     * @param TagInterface $tag
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(TagInterface $tag)
    {
        try {
            $this->tagResource->delete($tag);
            unset($this->tags[$tag->getTagId()]);
        } catch (\Exception $e) {
            if ($tag->getTagId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove tag with ID %1. Error: %2',
                        [$tag->getTagId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove tag. Error: %1', $e->getMessage()));
        }

        return true;
    }

    /**
     * @param int $tagId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($tagId)
    {
        $tagModel = $this->getById($tagId);
        $this->delete($tagModel);

        return true;
    }

    /**
     * @param string[] $tags
     *
     * @return \Amasty\Blog\Model\ResourceModel\Tag\Collection
     */
    public function getList($tags)
    {
        return $this->tagCollectionFactory->create()->addFieldToFilter(TagInterface::NAME, ['in' => $tags]);
    }

    /**
     * @return \Amasty\Blog\Model\Tag
     */
    public function getTagModel()
    {
        return $this->tagFactory->create();
    }

    /**
     * @return TagResource\Collection
     */
    public function getTagCollection()
    {
        return $this->tagCollectionFactory->create();
    }

    /**
     * @param $postId
     * @return TagResource\Collection
     */
    public function getTagsByPost($postId)
    {
        $tags = $this->tagCollectionFactory->create();
        $tags->addPostFilter($postId);

        return $tags;
    }

    /**
     * @param array $tagsIds
     * @return Collection
     */
    public function getTagsByIds($tagsIds = [])
    {
        if (!is_array($tagsIds)) {
            $tagsIds = explode(',', $tagsIds);
        }
        $tags = $this->tagCollectionFactory->create();
        $tags->addIdFilter($tagsIds);

        return $tags;
    }

    /**
     * @param null $storeId
     * @return TagResource\Collection
     * @throws NoSuchEntityException
     */
    public function getActiveTags($storeId = null)
    {
        $tags = $this->tagCollectionFactory->create();
        $store = $this->storeManagerInterface->isSingleStoreMode()
            ? null
            : $this->storeManagerInterface->getStore()->getId();
        $tags->addWeightData($storeId ?: $store);
        $tags->setPostStatusFilter(PostStatus::STATUS_ENABLED);

        return $tags;
    }

    /**
     * @return \Magento\Framework\DataObject[]
     */
    public function getAllTags()
    {
        return $this->tagCollectionFactory->create()->getItems();
    }
}
