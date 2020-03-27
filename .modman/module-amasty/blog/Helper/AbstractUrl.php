<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Helper;

/**
 * Class AbstractUrl
 */
class AbstractUrl extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ROUTE_POST = 'post';

    const ROUTE_CATEGORY = 'category';

    const ROUTE_TAG = 'tag';

    const ROUTE_ARCHIVE = 'archive';

    const ROUTE_SEARCH = 'search';

    const ROUTE_AUTHOR = 'author';

    /**
     * @var \Magento\Framework\App\Helper\Context
     */
    private $context;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->context = $context;
        $this->messageManager = $messageManager;
    }

    /**
     * @param $url
     *
     * @return bool is url valid
     */
    public function validate($url)
    {
        $isUrlValid = true;

        if (strpos($url, '/') !== false) {
            $isUrlValid = false;
            $this->messageManager->addErrorMessage(__('URL route and URL key are not allow /'));
        }

        return $isUrlValid;
    }

    /**
     * @param $url
     * @return string
     */
    public function prepare($url)
    {
        return str_replace('/', '', $url);
    }

    /**
     * @param $title
     * @return string|string[]|null
     */
    public function generate($title)
    {
        $title = preg_replace('/[«»""!?,.!@£$%^&*{};:()]+/', '', strtolower($title));
        $key = preg_replace('/[^A-Za-z0-9-]+/', '-', $title);

        return $key;
    }
}
