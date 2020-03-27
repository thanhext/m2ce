<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Api\Data;

interface AuthorInterface
{
    const AUTHOR_ID = 'author_id';

    const NAME = 'name';

    const GOOGLE_PROFILE = 'google_profile';

    const FACEBOOK_PROFILE = 'facebook_profile';

    const TWITTER_PROFILE = 'twitter_profile';

    const URL_KEY = 'url_key';

    const META_TITLE = 'meta_title';

    const META_TAGS = 'meta_tags';

    const META_DESCRIPTION = 'meta_description';

    /**
     * @return int
     */
    public function getAuthorId();

    /**
     * @param int $authorId
     *
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setAuthorId($authorId);

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @param string|null $name
     *
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setName($name);

    /**
     * @return string|null
     */
    public function getUrlKey();

    /**
     * @param string|null $urlKey
     *
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setUrlKey($urlKey);

    /**
     * @return string|null
     */
    public function getMetaTitle();

    /**
     * @param string|null $metaTitle
     *
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setMetaTitle($metaTitle);

    /**
     * @return string|null
     */
    public function getMetaTags();

    /**
     * @param string|null $metaTags
     *
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setMetaTags($metaTags);

    /**
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * @param string|null $metaDescription
     *
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * @param null $name
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function prepapreUrlKey($name = null);

    /**
     * @param string $profileLink
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setGoogleProfile($profileLink);

    /**
     * @return string
     */
    public function getFacebookProfile();

    /**
     * @param string $profileLink
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setFacebookProfile($profileLink);

    /**
     * @return string
     */
    public function getTwitterProfile();

    /**
     * @param string $profileLink
     * @return \Amasty\Blog\Api\Data\AuthorInterface
     */
    public function setTwitterProfile($profileLink);
}
