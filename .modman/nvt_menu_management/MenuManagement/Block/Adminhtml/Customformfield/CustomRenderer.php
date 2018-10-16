<?php
namespace NVT\MenuManagement\Block\Adminhtml\Customformfield;
/**
 * Class CustomRenderer
 * @package NVT\BannerManagement\Block\Adminhtml\Customformfield
 */
class CustomRenderer extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    const ITEM_TYPE_BRAND         = 'bra';
    const ITEM_TYPE_CATEGORY      = 'cat';
    const ITEM_TYPE_CMS_PAGE      = 'cms';
    const ITEM_TYPE_CUSTOM_URL    = 'oth';
    /**
     * @var \Magento\Cms\Helper\Page
     */
    protected $_pageHelper;
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_collectionCategory;

    /**
     * CustomRenderer constructor.
     * @param \Magento\Cms\Helper\Page $pageHelper
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionCategory
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param array $data
     */
    public function __construct(
        \Magento\Cms\Helper\Page $pageHelper,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionCategory,
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        $data = []
    ) {
        $this->_pageHelper = $pageHelper;
        $this->_pageFactory = $pageFactory;
        $this->_collectionCategory = $collectionCategory;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);

    }
    public function getPageUrl($pageId = null)
    {
        $url = $this->_pageHelper->getPageUrl($pageId);
        if(strpos($url, '?')){
            $tmp = explode('?', $url);
            $url = array_shift($tmp);
        }
        return $url; 
    }
    public function toOptionPageArray()
    {
        $collection = $this->_pageFactory->create()->getCollection();
        $options[] = ['value' => 0, 'label' =>  __('-- Please Select a Cms page --')];
        foreach($collection as $page) {
            $options[] = ['value' => $page->getPageId(), 'label' => $page->getTitle() .' (ID: '. $page->getPageId() . ')', 'url' => $this->getPageUrl($page->getPageId())];
        }
        return $options;
    }
    private function toOptionCategoryArray()
    {
        $collection = $this->_collectionCategory->create();
        $collection->addAttributeToSort('path', 'asc');
        $collection->addAttributeToSelect(array('name', 'entity_id'))->load();
        $options[] = ['value' => 0, 'label' =>  __('-- Please Select a Category --')];
        foreach ($collection as $category) {
            $lv = '|';
            if($category->getLevel() > 0){
                for ($i=0;$i < $category->getLevel(); $i++){
                    $lv .= '---';
                }
                $options[] = ['value' => $category->getId(), 'label' => $lv . ' ' . $category->getName() .' (ID: '. $category->getId() . ')', 'url' => $category->getUrl()];
            }
        }
        return $options;
    }
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value'=> self::ITEM_TYPE_CATEGORY, 'label'=>__('Category')],
            ['value' => self::ITEM_TYPE_BRAND, 'label' => __('Brand')],
            ['value' => self::ITEM_TYPE_CMS_PAGE, 'label' => __('CMS Page')],
            ['value' => self::ITEM_TYPE_CUSTOM_URL, 'label' => __('Custom URL')]

        ];
    }

    private function toOptionsHtml($options, $selected)
    {
        $html = '';
        if ($options) {
            foreach ($options as $option) {
                if (is_array($option['value'])) {
                    $html .= '<optgroup label="' . $option['label'] . '">' . "\n";
                    foreach ($option['value'] as $groupItem) {
                        $html .= $this->_optionToHtml($groupItem, $selected);
                    }
                    $html .= '</optgroup>' . "\n";
                } else {
                    $html .= $this->_optionToHtml($option, $selected);
                }
            }
        }
        return $html;
    }

    private function getElementTypeHtml(){
        $html = '';
        $value = $this->getValue();
        $html .= '<input type="hidden" id="' . $this->getHtmlId() . '" name="' . $this->getName() . '" value="'. $value .'" />';
        $html .= '<select id="' . $this->getHtmlId() . '_option" ' . $this->serialize(
                $this->getHtmlAttributes()
            ) . $this->_getUiId() . ' >' . "\n";
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        $type = array_shift($value);
        $values = $this->toOptionArray();
        $html .= $this->toOptionsHtml($values, $type);
        $html .= '</select>' . "\n";
        if($type == self::ITEM_TYPE_CMS_PAGE){
            $cat = '';
            $cms = array_shift($value);
        }
        else{
            $cms = '';
            $cat = array_shift($value);
        }


        //CMS Page
        $html .= '<select class="'. $this->getHtmlId() .'_change cms" name="cms_' . $this->getName() . '" >' . "\n";
        $optionCms = $this->toOptionPageArray();
        $html .= $this->toOptionsHtml($optionCms, $cms);
        $html .= '</select>' . "\n";

        //Category list
        $html .= '<select class="'. $this->getHtmlId() .'_change cat" name="cat_' . $this->getName() . '" >' . "\n";
        $optionCat = $this->toOptionCategoryArray();
        $html .= $this->toOptionsHtml($optionCat, $cat);
        $html .= '</select>' . "\n";

        return $html;
    }
    /**
     * Get the Html for the element.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $this->addClass('select multiselect admin__control-multiselect');
        $html = '';
        if ($this->getCanBeEmpty()) {
            $html .= '<input type="hidden" name="' . parent::getName() . '" value="" />';
        }
        $html .= $this->getElementTypeHtml();

        $html .= $this->getAfterElementHtml();

        return $html;
    }
    /**
     * @param array $option
     * @param array $selected
     * @return string
     */
    protected function _optionToHtml($option, $selected)
    {
        $html = '<option value="' . $this->_escape($option['value']) . '"';
        $html .= isset($option['title']) ? 'title="' . $this->_escape($option['title']) . '"' : '';
        $html .= isset($option['style']) ? 'style="' . $option['style'] . '"' : '';
        $html .= isset($option['url']) ? 'data-url="' . $option['url'] . '"' : '';
        if ($option['value'] == $selected) {
            $html .= ' selected="selected"';
        }
        $html .= '>' . $this->_escape($option['label']) . '</option>' . "\n";
        return $html;
    }
    /**
     * Get the after element html.
     *
     * @return mixed
     */
    public function getAfterElementHtml()
    {
        $result  = '';
        $result .= '<script>require(["jquery", "domReady!"], function($){';
        $result .= '    var htmlId = "'. $this->getHtmlId() . '";';
        $result .= '    $("body").on("change", "#"+ htmlId +"_option", function(){
                            var val = $(this).val(),
                                ele = $(this).closest(".admin__field.field").next();
                            $("#"+ htmlId +"_option option").each(function(i) {
                                var op = $(this).attr("value");
                                if(val == op){
                                    $("."+ htmlId +"_change."+ val).show();
                                    $("."+ htmlId +"_change."+ val).trigger("change");
                                    if(val == "cat" || val == "cms"){
                                        //ele.find("input").attr("disabled",true);
                                        ele.find("input").parent().addClass("disabled");
                                    }
                                    else{
                                        ele.find("input").attr("disabled",false);
                                    }
                                    var sel = $(this).val(),
                                        opt = $("."+ htmlId +"_change."+ val).val();
                                    $("#"+ htmlId).attr("value", sel +","+ opt);
                                }
                                else{
                                    $("."+ htmlId +"_change."+ op).hide();
                                }
                            });
                        });
                        $("#"+ htmlId +"_option").trigger("change");';

        $result .= '    $("body").on("change", "."+ htmlId +"_change", function(){
                            var typ = $("#"+ htmlId +"_option").val(),
                                sel = $(this).val(),
                                url = $(this).find(":selected").attr("data-url"),
                                ele = $(this).closest(".admin__field.field").next();
                                ele.find("input").val(url);
                                $("#"+ htmlId).attr("value", typ +","+ sel);
                        });';
        $result .= '});</script>';

        return $result;
    }
}