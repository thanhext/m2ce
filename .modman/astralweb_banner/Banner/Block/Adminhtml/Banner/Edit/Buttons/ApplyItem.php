<?php
namespace AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons;
/**
 * Class ApplyItem
 * @package AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons
 */
class ApplyItem extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    private $objectFactory;

    /**
     * ApplyItem constructor.
     * @param \Magento\Framework\DataObjectFactory $objectFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->objectFactory = $objectFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return bool|string
     */
    public function getConfig()
    {
        /** @var \Magento\Framework\DataObject $data */
        $data = $this->objectFactory->create();
        $urlReload = $this->getUrl(
            'mui/index/render',
            ['_query' => [
                'namespace' => 'banner_item_grid',
                'isAjax' => 'true'
            ]]
        );
        $url = $this->getUrl('banner/item/form');
        $data->setData('url', $url);
        $data->setData('url_reload', $urlReload);

        return $data->toJson();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
//    public function getApplyBannerItemBlock()
//    {
//        return $this->getLayout()->createBlock(
//            'AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Item\Edit',
//            'banner.edit.item'
//        )->toHtml();
//    }
}