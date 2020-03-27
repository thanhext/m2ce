<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Block\Sidebar;

/**
 * Class Search
 */
class Search extends AbstractClass
{
    /**
     * @var \Amasty\Blog\Helper\Url
     */
    private $urlHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        \Amasty\Blog\Helper\Date $dateHelper,
        \Amasty\Blog\Helper\Data $dataHelper,
        \Amasty\Blog\Helper\Url $urlHelper,
        array $data = []
    ) {
        parent::__construct($context, $settingsHelper, $dateHelper, $dataHelper, $data);
        $this->urlHelper = $urlHelper;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("Amasty_Blog::sidebar/search.phtml");
        $this->addAmpTemplate("Amasty_Blog::amp/sidebar/search.phtml");
        $this->setRoute('display_search');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getBlockHeader()
    {
        return __("Search The Blog");
    }

    /**
     * @return string
     */
    public function getSearchUrl()
    {
        return $this->urlHelper->setStoreId($this->getStoreId())
            ->getUrl(null, \Amasty\Blog\Helper\Url::ROUTE_SEARCH);
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->stripTags($this->getRequest()->getParam('query'));
    }

    /**
     * @return string
     */
    public function getAmpSearchUrl()
    {
        return str_replace(['https:', 'http:'], '', $this->getSearchUrl());
    }
}
