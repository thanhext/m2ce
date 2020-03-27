<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model;

use Amasty\Blog\Api\Data\TagInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Class
 */
class Tag extends \Magento\Framework\Model\AbstractModel implements IdentityInterface, TagInterface
{
    const PERSISTENT_NAME = 'amasty_blog_tags';

    const CACHE_TAG = 'amblog_tag';

    /**
     * @var \Amasty\Blog\Helper\Url
     */
    private $urlHelper;

    public function _construct()
    {
        parent::_construct();
        $this->urlHelper = $this->getData('url_helper');
        $this->_cacheTag = self::CACHE_TAG;
        $this->_init(\Amasty\Blog\Model\ResourceModel\Tag::class);
    }

    /**
     * @param int $page
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTagUrl($page = 1)
    {
        return $this->urlHelper
            ->setStoreId($this->getStoreId())
            ->getUrl($this, \Amasty\Blog\Helper\Url::ROUTE_TAG, $page);
    }

    /**
     * @param int $page
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrl($page)
    {
        return $this->getTagUrl($page);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [
            \Amasty\Blog\Model\Lists::CACHE_TAG,
            self::CACHE_TAG . '_' . $this->getId()
        ];

        return $identities;
    }

    /**
     * @return int
     */
    public function getTagId()
    {
        return $this->_getData(TagInterface::TAG_ID);
    }

    /**
     * @param int $tagId
     * @return $this|TagInterface
     */
    public function setTagId($tagId)
    {
        $this->setData(TagInterface::TAG_ID, $tagId);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->_getData(TagInterface::NAME);
    }

    /**
     * @param string|null $name
     * @return $this|TagInterface
     */
    public function setName($name)
    {
        $this->setData(TagInterface::NAME, $name);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlKey()
    {
        return $this->_getData(TagInterface::URL_KEY);
    }

    /**
     * @param string|null $urlKey
     * @return $this|TagInterface
     */
    public function setUrlKey($urlKey)
    {
        $this->setData(TagInterface::URL_KEY, $urlKey);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaTitle()
    {
        return $this->_getData(TagInterface::META_TITLE);
    }

    /**
     * @param string|null $metaTitle
     * @return $this|TagInterface
     */
    public function setMetaTitle($metaTitle)
    {
        $this->setData(TagInterface::META_TITLE, $metaTitle);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaTags()
    {
        return $this->_getData(TagInterface::META_TAGS);
    }

    /**
     * @param string|null $metaTags
     * @return $this|TagInterface
     */
    public function setMetaTags($metaTags)
    {
        $this->setData(TagInterface::META_TAGS, $metaTags);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->_getData(TagInterface::META_DESCRIPTION);
    }

    /**
     * @param string|null $metaDescription
     * @return $this|TagInterface
     */
    public function setMetaDescription($metaDescription)
    {
        $this->setData(TagInterface::META_DESCRIPTION, $metaDescription);

        return $this;
    }
}
