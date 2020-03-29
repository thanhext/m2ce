<?php

namespace T2N\BannerManager\Api\Data;

/**
 * Interface ItemInterface
 *
 * @api
 */
interface ItemInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const BANNER_ITEM_ID = 'entity_id';
    const BANNER_ID      = 'banner_id';
    const TITLE          = 'title';
    const IMAGE          = 'image';
    const LINK           = 'link';
    const DESCRIPTION    = 'description';
    const ACTION         = 'action';
    const CLASS_ACTION   = 'class_action';
    const CLASS_CONTENT  = 'class_content';
    const CREATION_TIME  = 'creation_time';
    const UPDATE_TIME    = 'update_time';
    const IS_ACTIVE      = 'is_active';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Banner ID
     *
     * @return int|null
     */
    public function getBannerId();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get link
     *
     * @return string|null
     */
    public function getLink();

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Get action
     *
     * @return string|null
     */
    public function getAction();

    /**
     * Get class action
     *
     * @return string|null
     */
    public function getClassAction();

    /**
     * Get class content
     *
     * @return string|null
     */
    public function getClassContent();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return ItemInterface
     */
    public function setId($id);

    /**
     * Set Banner ID
     *
     * @param int $id
     * @return ItemInterface
     */
    public function setBannerId($id);

    /**
     * Set Title
     *
     * @param string $title
     * @return ItemInterface
     */
    public function setTitle($title);

    /**
     * Set Image
     *
     * @param string $image
     * @return ItemInterface
     */
    public function setImage($image);

    /**
     * Set Link
     *
     * @param string $link
     * @return ItemInterface
     */
    public function setLink($link);

    /**
     * Set Description
     *
     * @param string $description
     * @return ItemInterface
     */
    public function setDescription($description);

    /**
     * Set Action
     *
     * @param string $action
     * @return ItemInterface
     */
    public function setAction($action);

    /**
     * Set Class Action
     *
     * @param string $class
     * @return ItemInterface
     */
    public function setClassAction($class);

    /**
     * Set Class Content
     *
     * @param string $class
     * @return ItemInterface
     */
    public function setClassContent($class);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return ItemInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return ItemInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return ItemInterface
     */
    public function setIsActive($isActive);
}
