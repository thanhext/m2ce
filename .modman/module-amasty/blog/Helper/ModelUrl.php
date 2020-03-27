<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Helper;

/**
 * Class ModelUrl
 */
class ModelUrl extends AbstractUrl
{
    /**
     * @var int
     */
    private $storeId;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Amasty\Blog\Helper\Settings $settings
    ) {
        parent::__construct($context, $messageManager);
        $this->storeManagerInterface = $storeManagerInterface;
        $this->settings = $settings;
    }

    /**
     * @param $model
     * @param $route
     * @param $url
     * @return string
     */
    private function getUrlByRoute($model, $route, $url)
    {
        $urlKey = $model->getUrlKey();
        if (!$urlKey) {
            return $url;
        }

        switch ($route) {
            case self::ROUTE_POST:
                $url .= "/" . $urlKey;
                break;
            case self::ROUTE_CATEGORY:
                $url .= "/" . self::ROUTE_CATEGORY . "/" . $urlKey;
                break;
            case self::ROUTE_TAG:
                $url .= "/" . self::ROUTE_TAG . "/" . $urlKey;
                break;
            case self::ROUTE_AUTHOR:
                $url .= "/" . self::ROUTE_AUTHOR . "/" . $urlKey;
                break;
        }

        return $url;
    }

    /**
     * @param null $model
     * @param string $route
     * @param int $page
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrl($model = null, $route = self::ROUTE_POST, $page = 1)
    {
        $storeId = $this->storeId ?: $this->storeManagerInterface->getStore()->getId();
        $baseUrl = $this->storeManagerInterface->getStore($storeId)->getBaseUrl();
        $url = $baseUrl . trim($this->settings->getSeoRoute());

        if ($model) {
            $url = $this->getUrlByRoute($model, $route, $url);
        }
        $postfix = $this->settings->getBlogPostfix();

        $url .= $page > 1 ? "/{$page}{$postfix}" : $postfix;

        return $url;
    }

    /**
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;

        return $this;
    }
}
