<?php

namespace T2N\SuggestProducts\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 *
 * @package Ecommage\Blog\Helper
 */
class Data extends AbstractHelper
{
    const XML_CONFIG_CATEGORY_ID = 'web/homepage/category_id';

    /**
     * @param $path
     *
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->getConfig(self::XML_CONFIG_CATEGORY_ID);
    }
}
