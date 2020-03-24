<?php
namespace T2N\BannerManager\Model\Banner;

/**
 * Class Item
 *
 * @package T2N\BannerManager\Model\Banner
 */
class Item extends \Magento\Framework\Model\AbstractModel implements
    \T2N\BannerManager\Api\Data\ItemInterface,
    \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'banner_item';

    protected $_eventPrefix = self::CACHE_TAG;

    protected $_eventObject = 'banner_item';

    protected $_idFieldName = 'entity_id';

    /**
     * Init
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(\T2N\BannerManager\Model\ResourceModel\Banner\Item::class);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
