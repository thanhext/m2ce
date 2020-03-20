<?php
namespace NVT\MenuManagement\Model\Config\Source;
/**
 * Class OptionMenu
 * @package NVT\MenuManagement\Model\Config\Source
 */
class OptionMenu implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \NVT\MenuManagement\Model\Menu
     */
    protected $_menuFactory;

    /**
     * OptionMenu constructor.
     * @param \NVT\MenuManagement\Model\Menu $menu
     */
    public function __construct(
        \NVT\MenuManagement\Model\Menu $menu
    ) {
        $this->_menuFactory = $menu;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray($showEmpty = true)
    {
        $options = [];
        if($showEmpty){
            $options[] = ['value' => '', 'label' => __('-- Choose menu --')];
        }
        $collection = $this->getMenuCollection();
        if($collection->count()){
            foreach($collection as $menu){
                $options[] = ['value' => $menu->getId(), 'label' => $menu->getTitle() .' (ID: '. $menu->getId() . ')'];
            }
        }
        return $options;
    }

    protected function getMenuCollection()
    {
        $collection = $this->_menuFactory->getCollection();
        $collection->addFieldToFilter('is_active', 1);
        return $collection;
    }
}