<?php
namespace T2N\BannerManager\Model\ResourceModel\Banner\Item;

/**
 * Class Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Init
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(
            \T2N\BannerManager\Model\Banner\Item::class,
            \T2N\BannerManager\Model\ResourceModel\Banner\Item::class
        );
    }
}
