<?php
namespace Ecommage\Blog\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Ecommage\Blog\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const FIELD_ID          = 'post_id';
    const FIELD_STATUS      = 'is_active';
    const FIELD_URL_KEY     = 'identifier';
    const FIELD_IMAGE_SRC   = 'featured_src';

    const ENTITY_TYPE_BLOG_POST = 'blog-post';
    const ROUTE_BLOG_LIST       = 'blog/';
    const ROUTE_BLOG_POST_DETAIL = 'blog/post/';
    const ERROR_MESSAGE_TITLE = 'The value specified in the URL Key field would generate a URL that already exists.';

    /**
     * Blog no-route config path
     */
    const XML_PATH_NO_ROUTE_POST = 'web/default/blog_no_route';

    /**
     * CMS no cookies config path
     */
    const XML_PATH_NO_COOKIES_POST = 'web/default/blog_no_cookies';

    /**
     * CMS home page config path
     */
    const XML_PATH_HOME_POST = 'web/default/blog_home_post';

    /**
     * Data constructor.
     * @param Context $context
     */
   public function __construct(Context $context)
   {
       parent::__construct($context);
   }

    /**
     * @param $path
     * @return mixed
     */
   public function getConfig($path)
   {
       return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
   }
}
