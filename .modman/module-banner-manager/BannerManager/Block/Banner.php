<?php

namespace T2N\BannerManager\Block;

use Magento\Framework\View\Element\Template;
use T2N\BannerManager\Model\Banner\Item;
use T2N\BannerManager\Model\System\Config\Type;

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
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $objectFactory;
    /**
     * @var string
     */
    protected $_template = 'T2N_BannerManager::slider.phtml';

    /**
     * Banner constructor.
     *
     * @param \Magento\Framework\DataObjectFactory        $objectFactory
     * @param \T2N\BannerManager\Model\BannerFactory      $bannerFactory
     * @param Template\Context                            $context
     * @param \T2N\BannerManager\Model\Banner\ItemFactory $bannerItemFactory
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Framework\DataObjectFactory $objectFactory,
        \T2N\BannerManager\Model\BannerFactory $bannerFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \T2N\BannerManager\Model\Banner\ItemFactory $bannerItemFactory,
        array $data = []
    ) {
        $this->_bannerItemFactory = $bannerItemFactory;
        $this->_bannerFactory     = $bannerFactory;
        $this->objectFactory      = $objectFactory;
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
                if ($banner->isActive()) {
                    return $banner;
                }
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
        if ($banner && $banner->getBannerItems()) {
            foreach ($banner->getBannerItems() as $item) {
                $items[] = $this->convertBannerItem($item);
            }
        }

        return $items;
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getBannerOptions()
    {
        $banner = $this->getBanner();
        $obj    = $this->objectFactory->create();
        if ($banner && $banner->getOptions()) {
            $obj->setData($banner->getOptions());
            return $obj;
        }

        return $obj;
    }

    /**
     * @return string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBannerConfigOptions()
    {
        $banner = $this->getBanner();
        if ($banner && $banner->getOptions()) {
            $options = $banner->getOptions();
            $json    = $banner->jsonEncode($options);
            return str_replace('"', '', $json);
        }

        return null;
    }

    /**
     * @param $data
     *
     * @return Item
     */
    protected function convertBannerItem($data)
    {
        if (is_array($data)) {
            /** @var \T2N\BannerManager\Model\Banner\Item $model */
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
            $identities = $banner->getIdentities();
        }

        $items = $this->getBannerItems();
        /** @var Item $item */
        foreach ($items as $item) {
            $identities = array_merge($identities, $item->getIdentities());
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
