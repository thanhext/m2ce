<?php
namespace AstralWeb\Banner\Model;

use Magento\Framework\Model\AbstractModel;
use AstralWeb\Banner\Api\Data\BannerInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Class Banner
 * @package AstralWeb\Banner\Model
 */
class Banner extends AbstractModel implements BannerInterface, IdentityInterface
{
    const CACHE_TAG = 'banner_b';

    /**#@+
     * banner's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'astralweb_banner';

    /**#@-*/

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\AstralWeb\Banner\Model\ResourceModel\Banner::class);
    }


    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    /**
     * Retrieve block id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::BLOCK_ID);
    }

    /**
     * Retrieve block identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return (string)$this->getData(self::IDENTIFIER);
    }

    /**
     * Retrieve block title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Retrieve block creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Retrieve block update time
     *
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return BannerInterface
     */
    public function setId($id)
    {
        return $this->setData(self::BLOCK_ID, $id);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return BannerInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return BannerInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }
    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return BannerInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return BannerInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return BannerInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Receive page store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Prepare block's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
