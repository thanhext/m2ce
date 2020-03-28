<?php

namespace T2N\BannerManager\Model\Banner;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use T2N\BannerManager\Api\Data\ItemInterface;

/**
 * Class Item
 *
 * @package T2N\BannerManager\Model\Banner
 */
class Item extends AbstractModel implements ItemInterface, IdentityInterface
{
    const CACHE_TAG = 'banner_item';
    /**
     * @var string
     */
    protected $_eventPrefix = self::CACHE_TAG;
    /**
     * @var string
     */
    protected $_eventObject = 'banner_item';
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Item constructor.
     *
     * @param Context               $context
     * @param Registry              $registry
     * @param StoreManagerInterface $storeManager
     * @param AbstractResource|null $resource
     * @param AbstractDb|null       $resourceCollection
     * @param array                 $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Returns image url
     *
     * @param string $attributeCode
     *
     * @return bool|string
     * @throws LocalizedException
     */
    public function getImageUrl($attributeCode = 'image')
    {
        $url   = false;
        $image = $this->getData($attributeCode);
        if ($image) {
            if (is_string($image)) {
                $store = $this->_storeManager->getStore();

                $isRelativeUrl = substr($image, 0, 1) === '/';

                $mediaBaseUrl = $store->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                );

                if ($isRelativeUrl) {
                    $url = $image;
                } else {
                    $url = $mediaBaseUrl
                           . ltrim(FileInfo::ENTITY_MEDIA_PATH, '/')
                           . '/'
                           . $image;
                }
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    /**
     * @return bool|\Zend_Validate_Interface
     */
    public function validate()
    {
        return parent::_createValidatorBeforeSave();
    }

    /**
     * Init
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(\T2N\BannerManager\Model\ResourceModel\Banner\Item::class);
    }
}
