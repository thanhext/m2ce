<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Block\Adminhtml;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton implements ButtonProviderInterface
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    /**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Registry $registry
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->registry = $registry;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        $id = $this->getItemId();
        if ($id) {
            $deleteUrl = $this->urlBuilder->getUrl('*/*/delete', ['id' => $id]);
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . $this->getConfirmText()
                    . '\', \'' . $deleteUrl . '\')',
                'sort_order' => 20,
            ];
        }

        return $data;
    }

    /**
     * @return \Magento\Framework\Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getConfirmText()
    {
        return __('Are you sure you want to delete this?');
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return 0;
    }
}
