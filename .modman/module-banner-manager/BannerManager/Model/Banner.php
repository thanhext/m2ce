<?php
namespace T2N\BannerManager\Model;

/**
 * Class Banner
 */
class Banner extends \Magento\Framework\Model\AbstractModel implements
    \T2N\BannerManager\Api\Data\BannerInterface,
    \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'banner_entity';

    protected $_eventPrefix = self::CACHE_TAG;

    protected $_eventObject = 'banner';

    protected $_idFieldName = 'entity_id';

    /**
     * Init
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(\T2N\BannerManager\Model\ResourceModel\Banner::class);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
