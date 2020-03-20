<?php
namespace NVT\MenuManagement\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class Menu
 * @package NVT\MenuManagement\Block
 */
class Menu extends Template
{
    /**
     *
     */
    protected $menuFactory;
    /**
     * Menu constructor.
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \NVT\MenuManagement\Model\MenuFactory $menuFactory,
        Template\Context $context,
        array $data
    )
    {
        $this->menuFactory = $menuFactory;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => [\NVT\MenuManagement\Model\Menu::CACHE_TAG,
            ], ]);
    }
    public function getMenu()
    {
        $menuId = $this->getMenuId();
        return $this->menuFactory->create()->load($menuId);
    }

    public function getMenuUrl($item)
    {
        return $item->getLink();
    }
}
