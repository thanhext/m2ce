<?php
namespace T2N\BannerManager\Api\Data;

/**
 * Interface BannerInterface
 *
 * @api
 */
interface BannerInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const BANNER_ID      = 'entity_id';
    const IDENTIFIER    = 'identifier';
    const TITLE         = 'title';
    const OPTIONS       = 'options';
    const BANNER_ITEMS  = 'banner_items';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
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
     * Get options
     *
     * @return string|null
     */
    public function getOptions();

    /**
     * Get banner items
     *
     * @return string|null
     */
    public function getBannerItems();

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
     * Set options
     *
     * @param string|array $options
     * @return BannerInterface
     */
    public function setOptions($options);

    /**
     * Set banner items
     *
     * @param string|array $items
     * @return BannerInterface
     */
    public function setBannerItems($items);

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
