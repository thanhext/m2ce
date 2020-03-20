<?php

namespace NVT\BannerManagement\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Slider extends Template implements BlockInterface
{
    protected $_banner;
    protected $_itemBanner;
    protected $_stdTimezone;
    protected $_template = "widget/slider.phtml";
    
    public function __construct(
        \NVT\BannerManagement\Model\Banner $banner,
        \NVT\BannerManagement\Model\ItemBanner $itemBanner,
        \Magento\Framework\Stdlib\DateTime\Timezone $_stdTimezone,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data
    )
    {
        $data = ['slider'=>1];
        $this->_banner = $banner;
        $this->_itemBanner = $itemBanner;
        $this->_stdTimezone = $_stdTimezone;
        parent::__construct($context, $data);
    }
    
    public function getSliderData()
    {
        $banerId = $this->getSlider();
        return $this->_banner->load($banerId);
    }

    public function getCollectionBannerItem()
    {
        $banerId = $this->getSlider();
        $dateTimeNow = $this->_stdTimezone->date()->format('Y-m-d H:i:s');
        $collections = $this->_itemBanner->getCollection();
        $collections->addFieldToFilter('banner_id', $banerId);
//        $collections->addFieldToFilter('is_active', \NVT\BannerManagement\Model\System\Config\Status::STATUS_ENABLED);
//        $collections->addFieldToFilter('start_time', [['to' => $dateTimeNow], ['start_time', 'null' => '']]);
//        $collections->addFieldToFilter('end_time', [['gteq' => $dateTimeNow], ['end_time', 'null' => '']]);
        $collections->setOrder('position', 'asc');
        return $collections;
    }

    public function getBaseUrlMedia($path = '', $secure = false)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path;
    }

}