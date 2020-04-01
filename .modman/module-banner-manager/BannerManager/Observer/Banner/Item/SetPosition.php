<?php

namespace T2N\BannerManager\Observer\Banner\Item;

use T2N\BannerManager\Model\Banner\Item;

/**
 * Class SetPosition
 */
class SetPosition implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Item $bannerItem */
        $bannerItem = $observer->getEvent()->getBannerItem();
        if (!$bannerItem->getPosition()) {
            $bannerItemId = $bannerItem->getId();
            $bannerItem->setPosition($bannerItemId);
            $bannerItem->save();
        }

        //$this->_prepareBanner($bannerItem);
    }

    private function _prepareBanner(Item $bannerItem)
    {
        $bannerItems = [];
        $collection = $bannerItem->getCollection();
        $collection->addFieldToFilter('banner_id', $bannerItem->getBannerId());
        foreach ($collection as $item) {
            $bannerItems[$item->getId()] = $item->getData();
        }
        $banner = $bannerItem->getBanner();
        $banner->setBannerItems($bannerItems);
        $banner->save();
    }
}
