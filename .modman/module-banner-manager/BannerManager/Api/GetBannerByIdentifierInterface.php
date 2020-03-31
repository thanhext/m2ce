<?php
namespace T2N\BannerManager\Api;

/**
 * Command to load the banner data by specified identifier
 * @api
 * @since 103.0.0
 */
interface GetBannerByIdentifierInterface
{
    /**
     * Load banner data by given banner identifier.
     *
     * @param string $identifier
     * @param int $storeId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \T2N\BannerManager\Api\Data\BannerInterface
     * @since 103.0.0
     */
    public function execute(string $identifier, int $storeId) : \T2N\BannerManager\Api\Data\BannerInterface;
}
