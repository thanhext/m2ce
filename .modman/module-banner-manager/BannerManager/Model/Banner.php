<?php

namespace T2N\BannerManager\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use T2N\BannerManager\Api\Data\BannerInterface;
use T2N\BannerManager\Model\ResourceModel\Banner\Item\Collection;
use T2N\BannerManager\Model\System\Config\Status;

/**
 * Class Banner
 *
 * @method Banner setStoreId(int $storeId)
 * @method int getStoreId()
 */
class Banner extends AbstractModel implements BannerInterface, IdentityInterface
{
    const CACHE_TAG = 'banner_entity';
    /**
     * @var string
     */
    protected $_eventPrefix = self::CACHE_TAG;
    /**
     * @var string
     */
    protected $_eventObject = 'banner';
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Init
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(\T2N\BannerManager\Model\ResourceModel\Banner::class);
    }

    /**
     * @param $data
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function jsonEncode($data)
    {
        if (is_array($data)) {
            $result = json_encode($data);
            if (false === $result) {
                throw new \Magento\Framework\Exception\LocalizedException("Unable to serialize value. Error: " . json_last_error_msg());
            }
            return $result;
        }

        return $data;
    }

    protected function jsonDecode($data)
    {
        if (is_string($data)) {
            $result = json_decode($data, true);
            if (false === $result) {
                throw new \Magento\Framework\Exception\LocalizedException("Unable to serialize value. Error: " . json_last_error_msg());
            }
            return $result;
        }

        return $data;
    }

    /**
     * Prevent banners recursion
     *
     * @return AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        if ($this->hasDataChanges()) {
            $this->setUpdateTime(null);
        }

        return parent::beforeSave();
    }

    /**
     * Get identities
     *
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    /**
     * Retrieve banner id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::BANNER_ID);
    }

    /**
     * Retrieve banner identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return (string)$this->getData(self::IDENTIFIER);
    }

    /**
     * Retrieve banner title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Retrieve banner options
     *
     * @return array|null
     */
    public function getOptions()
    {
        $data = $this->getData(self::OPTIONS);
        if (is_string($data)) {
            return $this->jsonDecode($data);
        }

        return $data;
    }

    /**
     * Retrieve banner items
     *
     * @return array|null
     */
    public function getBannerItems()
    {
        $data = $this->getData(self::BANNER_ITEMS);
        if (is_string($data)) {
            return $this->jsonDecode($data);
        }

        if (empty($data)) {
            $items = $this->getBannerItemsCollection();
            if ($items instanceof Collection) {
                $data = $items->getData();
            }
            $this->setBannerItems($data);
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getBannerItemsCollection()
    {
        return $this->getResource()->getBannerItems($this);
    }

    /**
     * Retrieve banner creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Retrieve banner update time
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
     *
     * @return BannerInterface
     */
    public function setId($id)
    {
        return $this->setData(self::BANNER_ID, $id);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     *
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
     *
     * @return BannerInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set options
     *
     * @param null|array $options
     *
     * @return BannerInterface
     */
    public function setOptions(array $options = null)
    {
        return $this->setData(self::OPTIONS, $this->jsonEncode($options));
    }

    /**
     * Set banner items
     *
     * @param null|array $items
     *
     * @return BannerInterface
     */
    public function setBannerItems(array $items = null)
    {
        return $this->setData(self::BANNER_ITEMS, $this->jsonEncode($items));
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     *
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
     *
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
     *
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
     * Prepare banner's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [Status::STATUS_ENABLED => __('Enabled'), Status::STATUS_DISABLED => __('Disabled')];
    }
}
