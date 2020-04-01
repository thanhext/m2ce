<?php

namespace T2N\BannerManager\Block;

use Magento\Framework\View\Element\Template;
use T2N\BannerManager\Model\Banner\Item;

/**
 * Banner content block
 */
class Banner extends Template implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * Banner item factory
     *
     * @var \T2N\BannerManager\Model\Banner\ItemFactory
     */
    protected $_bannerItemFactory;
    /**
     * Banner factory
     *
     * @var \T2N\BannerManager\Model\BannerFactory
     */
    protected $_bannerFactory;
    /**
     * @var string
     */
    protected $_template = 'T2N_BannerManager::banner.phtml';

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context    $context
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \T2N\BannerManager\Model\BannerFactory     $bannerFactory
     * @param array                                      $data
     */
    public function __construct(
        \T2N\BannerManager\Model\BannerFactory $bannerFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \T2N\BannerManager\Model\Banner\ItemFactory $bannerItemFactory,
        array $data = []
    ) {
        $this->_bannerItemFactory = $bannerItemFactory;
        $this->_bannerFactory     = $bannerFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return \T2N\BannerManager\Model\Banner|null
     */
    public function getBanner()
    {
        $bannerId = $this->getBannerId();
        try {
            if ($bannerId) {
                $storeId = $this->_storeManager->getStore()->getId();
                /** @var \T2N\BannerManager\Model\Banner $block */
                $banner = $this->_bannerFactory->create();
                $banner->setStoreId($storeId)->load($bannerId);
                return $banner;
            }
        } catch (\Exception $e) {
            $this->_logger->log($e->getMessage());
        }

        return null;
    }

    /**
     * @param $bannerItem
     *
     * @return string|null
     */
    public function getImageUrl($bannerItem)
    {
        try {
            return $bannerItem->getImageUrl();
        } catch (\Exception $e) {
            $this->_logger->log($e->getMessage());
        }

        return null;
    }

    /**
     * @return array
     */
    public function getBannerItems()
    {
        $items  = [];
        $banner = $this->getBanner();
        if ($banner) {
            foreach ($banner->getBannerItems() as $item) {
                $items[] = $this->convertBannerItem($item);
            }
        }

        return $items;
    }

    /**
     * @param $data
     *
     * @return Item
     */
    protected function convertBannerItem($data)
    {
        if (is_array($data)) {
            $model = $this->_bannerItemFactory->create();
            $model->setData($data);
            return $model;
        }

        return $data;
    }

    /**
     * @param $link
     *
     * @return string
     */
    public function getLink($link)
    {
        return $this->getBaseUrl() . $link;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        if ($banner = $this->getBanner()) {
            $identities[] = $banner->getIdentifier();
        }

        $items = $this->getBannerItems();
        /** @var Item $item */
        foreach ($items as $item) {
            $identities[] = $item->getIdentities();
        }

        return $identities;
    }

    /**
     * @inheritdoc
     */
    public function getCacheKeyInfo()
    {
        $cacheKeyInfo   = parent::getCacheKeyInfo();
        $cacheKeyInfo[] = $this->_storeManager->getStore()->getId();
        return $cacheKeyInfo;
    }
}
