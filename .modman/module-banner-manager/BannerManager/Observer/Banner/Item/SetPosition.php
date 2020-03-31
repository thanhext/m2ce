<?php

namespace T2N\BannerManager\Observer\Banner\Item;

/**
 * Class SetPosition
 */
class SetPosition implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $bannerItem = $observer->getEvent()->getBannerItem();
        if (!$bannerItem->getPosition()) {
            $bannerItemId = $bannerItem->getId();
            $bannerItem->setPosition($bannerItemId);
            $bannerItem->save();
        }
    }
}
