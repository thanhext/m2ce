<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Helper;

use Magento\Framework\Model\AbstractModel;

/**
 * Class
 */
class Url extends AbstractUrl
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

    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    private $pager;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var \Amasty\Blog\Api\PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var \Amasty\Blog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var \Amasty\Blog\Api\TagRepositoryInterface
     */
    private $tagRepository;

    /**
     * @var \Amasty\Blog\Api\AuthorRepositoryInterface
     */
    private $authorRepository;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Amasty\Blog\Helper\Settings $settings,
        \Magento\Theme\Block\Html\Pager $pager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Amasty\Blog\Api\PostRepositoryInterface $postRepository,
        \Amasty\Blog\Api\CategoryRepositoryInterface $categoryRepository,
        \Amasty\Blog\Api\TagRepositoryInterface $tagRepository,
        \Amasty\Blog\Api\AuthorRepositoryInterface $authorRepository
    ) {
        parent::__construct($context, $messageManager);
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->messageManager = $messageManager;
        $this->pager = $pager;
        $this->settings = $settings;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param $identifier
     * @return bool
     */
    public function getPostId($identifier)
    {
        $clean = $this->cleanUrl($identifier);
        $post = $this->postRepository->getByUrlKey($clean);

        return $post->getId() ?: false;
    }

    /**
     * @param $identifier
     * @param int $page
     * @return bool|string
     */
    private function cleanUrl($identifier, $page = 1)
    {
        $clean = substr($identifier, strlen($this->getRoute()), strlen($identifier));
        $clean = trim($clean, "/");
        $clean = str_replace([$this->getUrlPostfix($page),], "", $clean);
        $clean = urldecode($clean);

        return $clean;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return trim($this->settings->getSeoRoute());
    }

    /**
     * @param int $page
     * @return string
     */
    private function getUrlPostfix($page = 1)
    {
        $postfix = $this->settings->getBlogPostfix();

        return $page > 1 ? "/{$page}{$postfix}" : $postfix;
    }

    /**
     * @param int|AbstractModel $item
     * @param $route
     * @param $url
     * @return string
     */
    private function getUrlByRoute($item, $route, $url)
    {
        try {
            switch ($route) {
                case self::ROUTE_POST:
                    $post = is_object($item) ? $item : $this->postRepository->getById($item);
                    if ($post->getUrlKey()) {
                        $url .= "/" . $post->getUrlKey();
                    }
                    break;

                case self::ROUTE_CATEGORY:
                    $category = is_object($item) ? $item : $this->categoryRepository->getById($item);
                    if ($category->getUrlKey()) {
                        $url .= "/" . self::ROUTE_CATEGORY . "/" . $category->getUrlKey();
                    }
                    break;

                case self::ROUTE_TAG:
                    $tag = is_object($item) ? $item : $this->tagRepository->getById($item);
                    $url .= "/" . self::ROUTE_TAG . "/" . $tag->getUrlKey();
                    break;

                case self::ROUTE_ARCHIVE:
                    $id = is_object($item) ? $item->getId() : $item;
                    $url .= "/" . self::ROUTE_ARCHIVE . "/" . $id;
                    break;

                case self::ROUTE_AUTHOR:
                    $author = is_object($item) ? $item : $this->authorRepository->getById($item);
                    $url .= "/" . self::ROUTE_AUTHOR . "/" . $author->getUrlKey();
                    break;
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->_logger->critical($e->getMessage());
        }

        return $url;
    }

    /**
     * @param int|AbstractModel $item
     * @param string $route
     * @param int $page
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrl($item = null, $route = self::ROUTE_POST, $page = 1)
    {
        $storeId = $this->storeId ?: $this->storeManagerInterface->getStore()->getId();
        $baseUrl = $this->storeManagerInterface->getStore($storeId)->getBaseUrl();
        $url = $baseUrl . $this->getRoute();

        if ($item) {
            $url = $this->getUrlByRoute($item, $route, $url);
        } else {
            if ($route == self::ROUTE_SEARCH) {
                $url .= "/" . self::ROUTE_SEARCH;
            }
        }

        $url .= $this->getUrlPostfix($page);

        return $url;
    }

    /**
     * @param $identifier
     * @param $page
     * @param $route
     * @return bool
     */
    private function getUrlKey($identifier, $page, $route)
    {
        $clean = $this->cleanUrl($identifier, $page);

        if (strpos($clean, "/") === false) {
            return false;
        }

        $parts = explode("/", $clean);

        if ((count($parts) != 2) || $parts[0] !== $route) {
            return false;
        }

        $urlKey = $parts[1];

        return $urlKey;
    }

    /**
     * @param $identifier
     * @param int $page
     * @return bool
     */
    public function getCategoryId($identifier, $page = 1)
    {
        $categoryUrlKey = $this->getUrlKey($identifier, $page, self::ROUTE_CATEGORY);
        $category = $this->categoryRepository->getByUrlKey($categoryUrlKey);

        return $category->getId() ?: false;
    }

    /**
     * @param $identifier
     * @param int $page
     * @return bool
     */
    public function getTagId($identifier, $page = 1)
    {
        $tagUrlKey = $this->getUrlKey($identifier, $page, self::ROUTE_TAG);
        $tagUrlKey = urldecode($tagUrlKey);
        $tag = $this->tagRepository->getByUrlKey($tagUrlKey);

        return $tag->getId() ?: false;
    }

    /**
     * @param $identifier
     * @param int $page
     * @return bool
     */
    public function getAuthorId($identifier, $page = 1)
    {
        $authorUrlKey = $this->getUrlKey($identifier, $page, self::ROUTE_AUTHOR);
        $author = $this->authorRepository->getByUrlKey($authorUrlKey);
        return $author->getAuthorId();
    }

    /**
     * @param $identifier
     * @param int $page
     * @return bool
     */
    public function isIndexRequest($identifier, $page = 1)
    {
        if ($this->settings->getRedirectToSeoFormattedUrl()) {
            $identifier = str_replace([$this->getUrlPostfix($page), '/', '.html', '.htm'], "", $identifier);
        }

        return $identifier == $this->getRoute();
    }

    /**
     * @param $identifier
     * @param int $page
     * @return bool
     */
    public function getIsSearchRequest($identifier, $page = 1)
    {
        return $this->cleanUrl($identifier, $page) === self::ROUTE_SEARCH ?  true : false;
    }

    /**
     * @param $identifier
     * @param null $postId
     * @param string $route
     * @param int $page
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isRightSyntax($identifier, $postId = null, $route = self::ROUTE_POST, $page = 1)
    {
        if (!$this->settings->getRedirectToSeoFormattedUrl()) {
            return true;
        }

        $stdPage = (bool)$this->_getRequest()->getParam($this->pager->getPageVarName());
        $required = str_replace(
            $this->storeManagerInterface->getStore()->getBaseUrl(),
            "",
            $this->getUrl($postId, $route, $page)
        );

        return (strtolower($identifier) == strtolower($required) && !$stdPage);
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

    /**
     * @param $response
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function addAmpHeaders($response)
    {
        $baseUrl = $this->storeManagerInterface->getStore()->getBaseUrl();
        // @codingStandardsIgnoreLine
        $urlData = parse_url($baseUrl);
        $response
            ->setHeader(
                'Access-Control-Allow-Origin',
                'https://' . str_replace('.', '-', $urlData['host']) . '.cdn.ampproject.org'
            )
            ->setHeader(
                'AMP-Access-Control-Allow-Source-Origin',
                rtrim($this->storeManagerInterface->getStore()->getBaseUrl(), '/')
            )
            ->setHeader(
                'Access-Control-Allow-Headers',
                'Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token',
                true
            )
            ->setHeader('Access-Control-Expose-Headers', 'AMP-Access-Control-Allow-Source-Origin', true)
            ->setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS', true)
            ->setHeader('Access-Control-Allow-Credentials', 'true', true);
    }
}
