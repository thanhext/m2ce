<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Plugin\Block;

use Magento\Framework\Data\Tree\NodeFactory;
use Amasty\Blog\Block\Link;
use Amasty\Blog\Helper\Url;

/**
 * Class
 */
class Topmenu
{
    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var Link
     */
    protected $link;

    /**
     * @var Url
     */
    protected $url;

    public function __construct(
        NodeFactory $nodeFactory,
        Link $link,
        Url $url
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->link = $link;
        $this->url = $url;
    }

    /**
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        if ($this->link->showInNavMenu()) {
            $node = $this->nodeFactory->create(
                [
                    'data' => $this->getNodeAsArray(),
                    'idField' => 'id',
                    'tree' => $subject->getMenu()->getTree()
                ]
            );
            $subject->getMenu()->addChild($node);
        }
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getNodeAsArray()
    {
        return [
            'name' => $this->link->getLabel(),
            'id' => 'navmenu_blog',
            'url' => $this->url->getUrl(),
            'has_active' => false,
            'is_active' => false
        ];
    }
}
