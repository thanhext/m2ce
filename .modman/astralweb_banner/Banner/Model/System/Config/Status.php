<?php
namespace AstralWeb\Banner\Model\System\Config;

/**
 * Class Status
 * @package AstralWeb\Banner\Model\System\Config
 */
class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \AstralWeb\Banner\Model\Banner
     */
    protected $banner;

    /**
     * Status constructor.
     * @param \AstralWeb\Banner\Model\Banner $banner
     */
    public function __construct(\AstralWeb\Banner\Model\Banner $banner)
    {
        $this->banner = $banner;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $status = [];
        $options = $this->toArray();
        foreach ($options as $value => $label) {
            $status[] = ['value' => $value, 'label' => $label];
        }
        return $status;
    }

    /**
     * @return array
     */
    public function toArray()
    {
       return $this->banner->getAvailableStatuses();
    }
}
