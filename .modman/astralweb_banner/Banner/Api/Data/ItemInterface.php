<?php
namespace AstralWeb\Banner\Api\Data;
/**
 * Interface ItemInterface
 * @package AstralWeb\Banner\Api\Data
 */
interface ItemInterface 
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ITEM_ID       = 'item_id';
    const TITLE         = 'title';
    const TYPE          = 'type';
    const IMAGE_DESKTOP = 'image_desktop';
    const IMAGE_MOBILE  = 'image_mobile';
    const ITEM_LINK     = 'item_link';
    const SORT_ORDER    = 'sort_order';
    const SUFFIX_CLASS  = 'suffix_class';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
    const ADDITIONALS   = 'additionals';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

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
     * @return BannerInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return BannerInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set title
     *
     * @param string $title
     * @return BannerInterface
     */
    public function setTitle($title);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return BannerInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return BannerInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return BannerInterface
     */
    public function setIsActive($isActive);
}