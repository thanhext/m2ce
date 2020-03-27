<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Block\Content\Lists;

use Amasty\Blog\Helper\Url as UrlHelper;

/**
 * Class Pager
 */
class Pager extends \Magento\Theme\Block\Html\Pager
{
    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    private $settings;

    /**
     * @var bool
     */
    private $isSearch = false;

    /**
     * @var null
     */
    private $object = null;

    /**
     * @var null
     */
    private $urlPostfix = null;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Settings $settings,
        UrlHelper $urlHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->settings = $settings;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param $object
     * @return $this
     */
    public function setPagerObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return null
     */
    public function getPagerObject()
    {
        return $this->object;
    }

    /**
     * @param bool $isSearch
     * @return $this
     */
    public function setSearchPage($isSearch)
    {
        $this->isSearch = $isSearch;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSearchPage()
    {
        return $this->isSearch;
    }

    /**
     * @param string $page
     * @return string
     */
    public function getPageUrl($page)
    {
        $url = $this->isSearchPage()
            ? $this->urlHelper->getUrl(null, UrlHelper::ROUTE_SEARCH, $page)
            : $this->getPagerObject()->getUrl($page);

        return $url . $this->getUrlPostfix();
    }

    /**
     * @return bool
     */
    public function isOldStyle()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getColorClass()
    {
        return $this->settings->getIconColorClass();
    }

    /**
     * Get Url Postfix
     *
     * @return null
     */
    public function getUrlPostfix()
    {
        return $this->urlPostfix;
    }

    /**
     * Set URL postfix
     *
     * @param $urlPostfix
     *
     * @return $this
     */
    public function setUrlPostfix($urlPostfix)
    {
        $this->urlPostfix = $urlPostfix;

        return $this;
    }

    /**
     * Return current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        if (is_object($this->_collection)) {
            return $this->_collection->getCurPage();
        }

        $pageNum = (int)$this->getRequest()->getParam($this->getPageVarName());

        return $pageNum ?: 1;
    }
}
