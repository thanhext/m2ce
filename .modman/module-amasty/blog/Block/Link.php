<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Block;

use Amasty\Blog\Helper\Settings;

/**
 * Class Link
 */
class Link extends \Magento\Framework\View\Element\Html\Link\Current
{
    /**
     * @var Settings
     */
    private $settingsHelper;

    /**
     * @var \Amasty\Blog\Helper\Url
     */
    private $urlHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \Amasty\Blog\Helper\Url $urlHelper,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
        $this->settingsHelper = $settingsHelper;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->urlHelper->getUrl();
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->settingsHelper->getBlogLabel();
    }

    /**
     * @return bool
     */
    public function showInNavMenu()
    {
        return $this->settingsHelper->showInNavMenu();
    }
}
