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
    const FIELD_ID      = 'post_id';
    const FIELD_STATUS  = 'is_active';
    const FIELD_URL_KEY = 'identifier';

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
