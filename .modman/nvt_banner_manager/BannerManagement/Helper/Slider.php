<?php
namespace NVT\BannerManagement\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;

class Slider extends AbstractHelper
{
    const BANNER_PATH_CONFIG = 'banner';

    protected $_item;
    protected $_html;
    protected $_class;
    protected $_itemFactory;
    protected $_storeManager;
    protected $_imageFile;
    protected $_assetRepo;
    protected $_isfrontend = true;

    /**
     * Image constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \NVT\BannerManagement\Model\ItemFactory $itemFactory
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\View\ConfigInterface $viewConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \NVT\BannerManagement\Model\ItemFactory $itemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig
    ) {
        $this->_itemFactory = $itemFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
        $this->_assetRepo = $assetRepo;
        $this->viewConfig = $viewConfig;
    }

    /**
     * Reset all previous data
     *
     * @return $this
     */
    protected function _reset()
    {
        $this->_html = null;
        $this->_item = null;
        $this->_imageFile = null;
        $this->_class = null;
        return $this;
    }

    /**
     * Initialize Helper to work with Item
     *
     * @param \NVT\BannerManagement\Model\Item $item
     * @return $this
     */
    public function init($item, $class = null)
    {
        $this->_reset();
        $this->setItem($item);
        $this->setClass($class);
        $this->setImageProperties();
        return $this;
    }

    /**
     * Set current Item
     *
     * @param \NVT\BannerManagement\Model\Item $item
     * @return $this
     */
    protected function setItem($item)
    {
        $this->_item = $item;
        return $this;
    }
    protected function setClass($class)
    {
        if(is_array($class)){
            $rclass = implode(' ', $class);
        }
        else {
            $rclass = $class;
        }
        $rclass .= ' item-wrapper item-'. $this->_item->getId();
        $this->_class = trim($rclass);
    }
    public function getClass()
    {
        return $this->_class;
    }
    /**
     * Set image properties
     *
     * @return $this
     */
    protected function setImageProperties()
    {
        $html  = '<div class="'. $this->getClass() .'" style="position: relative;z-index: 1;" >';
        $html .= '  <img src="'. $this->getImageSrc() .'" alt="'. $this->getAlt() .'" />';
        $html .= '  <div class="caption" style="position: absolute;z-index: 2;' . $this->_item->getStyle() .'">'. $this->getLabel() .'</div>';
        $html .= '</div>';
        $this->_html = $html;
        return $this;
    }
    public function toHtml()
    {
        return $this->_html;
    }
    /**
     * @return string
     */
    public function getImageSrc()
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, false)
                . $this->_item->getImage();
    }

    /**
     * Get current Image model
     *
     * @return \NVT\BannerManagement\Model\ItemFactory
     */
    protected function _getModel()
    {
        if (!$this->_model) {
            $this->_model = $this->_itemFactory->create();
        }
        return $this->_model;
    }


    /**
     * Get current Item
     *
     * @return \NVT\BannerManagement\Model\Item
     */
    protected function getItem()
    {
        return $this->_item;
    }

    /**
     * Set Image file
     *
     * @param string $file
     * @return $this
     */
    public function setImageFile($file)
    {
        $this->_imageFile = $file;
        return $this;
    }

    /**
     * Get Image file
     *
     * @return string
     */
    protected function getImageFile()
    {
        return $this->_imageFile;
    }
    /**
     * Return image label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_item->getDescription();
    }
    public function getAlt()
    {
        return $this->_item->getTitle();
    }
}
