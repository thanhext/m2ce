<?php
namespace T2N\BannerManager\Model\ResourceModel;

/**
 * Class Banner
 */
class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Init
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init('banner_entity', 'entity_id');
    }
}
