<?php
namespace T2N\AdminLogo\Block\Page;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class Header extends \Magento\Backend\Block\Page\Header
{
    /**
     * @var string
     */
    protected $_template = 'T2N_AdminLogo::page/header.phtml';

    protected $_scopeConfig;

    protected $_request;

    public function __construct(
        Context $context,
        Session $authSession,
        Data $backendData,
        ScopeConfigInterface $scopeConfig,
        Http $request,
        array $data = []
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_request = $request;
        parent::__construct($context, $authSession, $backendData, $data);
    }

    public function getLogoConfig()
    {
        return $this->_scopeConfig->getValue('admin/general/logo', ScopeInterface::SCOPE_STORE);
    }

    public function getIconConfig()
    {
        return $this->_scopeConfig->getValue('admin/general/icon', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $url
     * @return string
     * @throws NoSuchEntityException
     */
    public function getIconUrl($url)
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'logo/adminicon/' . $url;
        return $mediaUrl;
    }

    /**
     * @param $url
     * @return string
     * @throws NoSuchEntityException
     */
    public function getLogoUrl($url)
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'logo/adminlogo/' . $url;
        return $mediaUrl;
    }

    public function getCurrentHandle()
    {
        return $this->_request->getFullActionName();
    }
}
