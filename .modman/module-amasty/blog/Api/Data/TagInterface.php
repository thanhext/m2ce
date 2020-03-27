<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Api\Data;

interface TagInterface
{
    const TAG_ID = 'tag_id';

    const NAME = 'name';

    const URL_KEY = 'url_key';

    const META_TITLE = 'meta_title';

    const META_TAGS = 'meta_tags';

    const META_DESCRIPTION = 'meta_description';

    /**
     * @return int
     */
    public function getTagId();

    /**
     * @param int $tagId
     *
     * @return \Amasty\Blog\Api\Data\TagInterface
     */
    public function setTagId($tagId);

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @param string|null $name
     *
     * @return \Amasty\Blog\Api\Data\TagInterface
     */
    public function setName($name);

    /**
     * @return string|null
     */
    public function getUrlKey();

    /**
     * @param string|null $urlKey
     *
     * @return \Amasty\Blog\Api\Data\TagInterface
     */
    public function setUrlKey($urlKey);

    /**
     * @return string|null
     */
    public function getMetaTitle();

    /**
     * @param string|null $metaTitle
     *
     * @return \Amasty\Blog\Api\Data\TagInterface
     */
    public function setMetaTitle($metaTitle);

    /**
     * @return string|null
     */
    public function getMetaTags();

    /**
     * @param string|null $metaTags
     *
     * @return \Amasty\Blog\Api\Data\TagInterface
     */
    public function setMetaTags($metaTags);

    /**
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * @param string|null $metaDescription
     *
     * @return \Amasty\Blog\Api\Data\TagInterface
     */
    public function setMetaDescription($metaDescription);
}
