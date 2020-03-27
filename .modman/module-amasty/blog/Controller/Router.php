<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller;

use Amasty\Blog\Helper\Url;

/**
 * Class Router
 */
class Router implements \Magento\Framework\App\RouterInterface
{
    const FLAG_REDIRECT = 'amplog_redirect_flag';

    const MAX_REDIRECT_COUNT = 3;

    /** @var \Magento\Framework\App\ActionFactory */
    private $actionFactory;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    private $settings;

    /**
     * @var \Amasty\Blog\Model\Router\Action
     */
    private $actionRouter;

    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    private $pager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieManager;

    /**
     * @var \Amasty\Blog\Helper\Data
     */
    private $dataHelper;

    /**
     * @var string
     */
    private $controlName = 'index';

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        Url $url,
        \Amasty\Blog\Helper\Settings $settings,
        \Amasty\Blog\Model\Router\Action $actionRouter,
        \Magento\Framework\Stdlib\Cookie\PhpCookieManager $cookieManager,
        \Magento\Theme\Block\Html\Pager $pager,
        \Amasty\Blog\Helper\Data $dataHelper
    ) {
        $this->actionFactory = $actionFactory;
        $this->url = $url;
        $this->settings = $settings;
        $this->actionRouter = $actionRouter;
        $this->pager = $pager;
        $this->cookieManager = $cookieManager;
        $this->dataHelper = $dataHelper;
    }

    /**
     * Response Current Page
     *
     * @param string $url
     *
     * @return int|boolean
     */
    public function responsePage($url)
    {
        $pattern = "/\/([\d]{1,}){$this->settings->getBlogPostfix()}$/i";
        preg_match_all($pattern, $url, $matches);
        if (!empty($matches[1])) {
            $page = $matches[1][0];
            if ($page > 1) {
                return (int)$page;
            }
        }

        return false;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return bool|\Magento\Framework\App\ActionInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $this->detectRedirect($request);

        # Result Action
        if ($this->actionRouter->getResult()) {
            # Redirect Flag
            if ($this->actionRouter->getIsRedirect()) {
                $this->redirectFlagUp();
            } else {
                $this->redirectFlagDown();
            }

            # Request Route
            $request->setModuleName($this->actionRouter->getModuleName())
                ->setControllerName($this->actionRouter->getControllerName())
                ->setActionName($this->actionRouter->getActionName());

            # Alias
            $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $this->actionRouter->getAlias());

            # Transfer Params
            foreach ($this->actionRouter->getParams() as $key => $value) {
                $request->setParam($key, $value);
            }

            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        } else {
            return false;
        }
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function redirectFlagUp()
    {
        $flag = $this->getRedirectFlag();
        $value = $flag ? ++$flag : 1;
        $this->cookieManager->setPublicCookie(self::FLAG_REDIRECT, $value);

        return $this;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function redirectFlagDown()
    {
        $this->cookieManager->deleteCookie(self::FLAG_REDIRECT);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectFlag()
    {
        return $this->cookieManager->getCookie(self::FLAG_REDIRECT);
    }

    /**
     * @param $request
     */
    private function detectRedirect($request)
    {
        $identifier = $request->getOriginalPathInfo();

        if (isset($identifier[0]) && $identifier[0] == '/') {
            $identifier = substr($identifier, 1, strlen($identifier));
        }

        $route = $this->url->getRoute();

        if (strpos($identifier, 'amp/' . $route) !== false
            && $this->dataHelper->isMobile()
            && $this->dataHelper->isAmpEnable()
        ) {
            $this->controlName = 'amp';
            $identifier = str_replace('amp/', '', $identifier);
        }

        $wrongPage = $request->getParam($this->pager->getPageVarName())
            ? $request->getParam($this->pager->getPageVarName())
            : 1;

        $page = $this->responsePage($identifier);

        if ($postId = $this->url->getPostId($identifier)) {
            $this->postRedirect($postId, $identifier);
        } elseif ($categoryId = $this->url->getCategoryId($identifier, $page)) {
            $this->categoryRedirect($categoryId, $identifier, $page, $wrongPage);
        } elseif ($tagId = $this->url->getTagId($identifier, $page)) {
            $this->tagRedirect($tagId, $identifier, $page, $wrongPage);
        } elseif ($this->url->isIndexRequest($identifier, $page)) {
            $this->blogRedirect($identifier, $page, $wrongPage);
        } elseif ($authorId = $this->url->getAuthorId($identifier, $page)) {
            $this->authorRedirect($authorId, $identifier, $page, $wrongPage);
        } elseif ($this->url->getIsSearchRequest($identifier, $page)) {
            $this->fillActionRouter(false, $identifier, 'search', $this->pager->getPageVarName(), $page);
        }
    }

    /**
     * @param $isRedirect
     * @param $flag
     * @param $action
     * @param $keyParam
     * @param $param
     */
    private function fillActionRouter($isRedirect, $flag, $action, $keyParam, $param)
    {
        $this->actionRouter->setIsRedirect($isRedirect)
            ->setRedirectFlag($flag)
            ->setModuleName('amblog')
            ->setControllerName($this->controlName)
            ->setActionName($action)
            ->setParam($keyParam, $param)
            ->setAlias($flag)
            ->setResult(true);
    }

    /**
     * @param $postId
     * @param $identifier
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function postRedirect($postId, $identifier)
    {
        if ($postId
            && !$this->url->isRightSyntax($identifier, $postId)
            && ($this->getRedirectFlag() < self::MAX_REDIRECT_COUNT)
        ) {
            $this->fillActionRouter(
                true,
                $identifier,
                'redirect',
                'url',
                $this->url->getUrl($postId)
            );
        } else {
            $this->fillActionRouter(false, $identifier, 'post', 'id', $postId);
        }
    }

    /**
     * @param $categoryId
     * @param $identifier
     * @param $page
     * @param $wrongPage
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function categoryRedirect($categoryId, $identifier, $page, $wrongPage)
    {
        $isRightSyntax = $this->url->isRightSyntax(
            $identifier,
            $categoryId,
            \Amasty\Blog\Helper\Url::ROUTE_CATEGORY,
            $page ? $page : $wrongPage
        );
        if ($categoryId && !$isRightSyntax && ($this->getRedirectFlag() < self::MAX_REDIRECT_COUNT)) {
            $url = $this->url->getUrl(
                $categoryId,
                \Amasty\Blog\Helper\Url::ROUTE_CATEGORY,
                $wrongPage ? $wrongPage : $page
            );
            $this->fillActionRouter(true, $identifier, 'redirect', 'url', $url);
        } else {
            $this->fillActionRouter(false, $identifier, 'category', 'id', $categoryId);
            $this->actionRouter->setParam($this->pager->getPageVarName(), $page);
        }
    }

    /**
     * @param $tagId
     * @param $identifier
     * @param $page
     * @param $wrongPage
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function tagRedirect($tagId, $identifier, $page, $wrongPage)
    {
        $isRightSyntax = $this->url->isRightSyntax(
            $identifier,
            $tagId,
            \Amasty\Blog\Helper\Url::ROUTE_TAG,
            $page ? $page : $wrongPage
        );
        if ($tagId && !$isRightSyntax && ($this->getRedirectFlag() < self::MAX_REDIRECT_COUNT)) {
            $url = $this->url->getUrl($tagId, \Amasty\Blog\Helper\Url::ROUTE_TAG, $wrongPage ? $wrongPage : $page);
            $this->fillActionRouter(true, $identifier, 'redirect', 'url', $url);
        } else {
            $this->fillActionRouter(false, $identifier, 'tag', 'id', $tagId);
            $this->actionRouter->setParam($this->pager->getPageVarName(), $page);
        }
    }

    /**
     * @param $urlKey
     * @param $identifier
     * @param $page
     * @param $wrongPage
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function authorRedirect($authorId, $identifier, $page, $wrongPage)
    {
        $isRightSyntax = $this->url->isRightSyntax(
            $identifier,
            $authorId,
            \Amasty\Blog\Helper\Url::ROUTE_AUTHOR,
            $page ? $page : $wrongPage
        );
        if ($authorId && !$isRightSyntax && ($this->getRedirectFlag() < self::MAX_REDIRECT_COUNT)) {
            $url =
                $this->url->getUrl($authorId, \Amasty\Blog\Helper\Url::ROUTE_AUTHOR, $wrongPage ? $wrongPage : $page);
            $this->fillActionRouter(true, $identifier, 'redirect', 'url', $url);
        } else {
            $this->fillActionRouter(false, $identifier, 'author', 'id', $authorId);
            $this->actionRouter->setParam($this->pager->getPageVarName(), $page);
        }
    }

    /**
     * @param $identifier
     * @param $page
     * @param $wrongPage
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function blogRedirect($identifier, $page, $wrongPage)
    {
        $isRightSyntax = $this->url->isRightSyntax(
            $identifier,
            null,
            \Amasty\Blog\Helper\Url::ROUTE_POST,
            $page ? $page : $wrongPage
        );
        if ($this->url->isIndexRequest($identifier, $page)
            && !$isRightSyntax
            && ($this->getRedirectFlag() < self::MAX_REDIRECT_COUNT)
        ) {
            $url = $this->url->getUrl(
                null,
                \Amasty\Blog\Helper\Url::ROUTE_POST,
                $wrongPage ? $wrongPage : $page
            );
            $this->fillActionRouter(true, $identifier, 'redirect', 'url', $url);
        } else {
            $this->fillActionRouter(false, $identifier, 'index', $this->pager->getPageVarName(), $page);
        }
    }
}
