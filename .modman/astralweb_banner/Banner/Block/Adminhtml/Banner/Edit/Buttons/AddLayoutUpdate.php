<?php
namespace AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons;

use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class AddLayoutUpdate
 * @package AstralWeb\Banner\Block\Adminhtml\Banner\Edit\Buttons
 */
class AddLayoutUpdate extends \Magento\Widget\Block\Adminhtml\Widget\Instance\Edit\Tab\Main\Layout
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_objectFactory;
    /**
     * @var string
     */
    protected $_template = 'AstralWeb_Banner::add-layout-update.phtml';

    /**
     * AddLayoutUpdate constructor.
     * @param \Magento\Framework\DataObjectFactory $objectFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Catalog\Model\Product\Type $productType
     * @param Json|null $serializer
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\Product\Type $productType,
        Json $serializer = null,
        array $data = []
    ) {
        $this->_objectFactory = $objectFactory;
        parent::__construct($context, $productType, $data, $serializer);
    }

    public function getThemeId()
    {
        return 5;
    }

    /**
     * Generate url to get categories chooser by ajax query
     *
     * @return string
     */
    public function getCategoriesChooserUrl()
    {
        return $this->getUrl('adminhtml/widget_instance/categories', ['_current' => true]);
    }

    /**
     * Generate url to get products chooser by ajax query
     *
     * @return string
     */
    public function getProductsChooserUrl()
    {
        return $this->getUrl('adminhtml/widget_instance/products', ['_current' => true]);
    }

    /**
     * Generate url to get reference block chooser by ajax query
     *
     * @return string
     */
    public function getBlockChooserUrl()
    {
        return $this->getUrl('adminhtml/widget_instance/blocks', ['_current' => true]);
    }

    /**
     * Generate url to get template chooser by ajax query
     *
     * @return string
     */
    public function getTemplateChooserUrl()
    {
        return $this->getUrl('adminhtml/widget_instance/template', ['_current' => true]);
    }

    /**
     * Retrieve layout select chooser html
     *
     * @return string
     */
    public function getLayoutsChooser()
    {
        $chooserBlock = $this->getLayout()->createBlock(
            \Magento\Widget\Block\Adminhtml\Widget\Instance\Edit\Chooser\Layout::class
        )->setName(
            'widget_instance[<%- data.id %>][pages][layout_handle]'
        )->setId(
            'layout_handle'
        )->setClass(
            'required-entry select'
        )->setExtraParams(
            "onchange=\"WidgetInstance.loadSelectBoxByType(\'block_reference\', " .
            "this.up(\'div.pages\'), this.value)\""
        )->setArea(
            'frontend'
        )->setTheme(
            $this->getThemeId()
        );
        return $chooserBlock->toHtml();
    }

    /**
     * Retrieve layout select chooser html
     *
     * @return string
     */
    public function getPageLayoutsPageChooser()
    {
        $chooserBlock = $this->getLayout()->createBlock(
            \Magento\Widget\Block\Adminhtml\Widget\Instance\Edit\Chooser\DesignAbstraction::class
        )->setName(
            'widget_instance[<%- data.id %>][page_layouts][layout_handle]'
        )->setId(
            'layout_handle'
        )->setClass(
            'required-entry select'
        )->setExtraParams(
            "onchange=\"WidgetInstance.loadSelectBoxByType(\'block_reference\', " .
            "this.up(\'div.pages\'), this.value)\""
        )->setArea(
            'frontend'
        )->setTheme(
            $this->getThemeId()
        );
        return $chooserBlock->toHtml();
    }

    /**
     * Prepare and retrieve page groups data of widget instance
     *
     * @return array
     */
    public function getPageGroups()
    {
        $pageGroups = [];
        // TODO
        return $pageGroups;
    }

    /**
     * @return bool|string
     */
    public function getConfig()
    {
        /** @var \Magento\Framework\DataObject $data */
        $data = $this->_objectFactory->create();
        $urlReload = $this->getUrl(
            'mui/index/render',
            ['_query' => [
                'namespace' => 'banner_item_grid',
                'isAjax' => 'true'
            ]]
        );
        $url = $this->getUrl('banner/layout/update');
        $data->setData('url', $url);
        $data->setData('url_reload', $urlReload);

        return $data->toJson();
    }


}