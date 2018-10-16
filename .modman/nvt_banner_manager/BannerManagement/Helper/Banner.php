<?php
namespace NVT\BannerManagement\Helper;

use Magento\Framework\App\Area;

class Banner extends \Magento\Framework\App\Helper\AbstractHelper
{
    const BANNER_PATH_CONFIG = 'banner';

    protected $_banner;
    protected $_html;
    protected $_class;
    protected $_itemFactory;
    protected $_storeManager;
    protected $_imageFile;
    protected $_assetRepo;
    protected $_isfrontend = true;
    protected $registry;

    /**
     * Image constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \NVT\BannerManagement\Model\ItemFactory $itemFactory
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\View\ConfigInterface $viewConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $registry,
        \NVT\BannerManagement\Model\ItemFactory $itemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig
    ) {
        $this->registry     = $registry;
        $this->_itemFactory = $itemFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
        $this->_assetRepo = $assetRepo;
        $this->viewConfig = $viewConfig;
    }

    /**
     * Reset all previous data
     *
     * @return $this
     */
    protected function _reset()
    {
        $this->_html = null;
        $this->_item = null;
        $this->_imageFile = null;
        $this->_class = null;
        return $this;
    }

    public function getCurrentItem() {
        return $this->registry->registry('item');
    }


    public function getImageToHtml() {
        $html  = '<div class="'. $this->getClass() .'" style="position: relative;z-index: 1;" >';
        $html .= '  <img src="'. $this->getImageSrc() .'" alt="'. $this->getAlt() .'" />';
        $html .= '  <div class="caption" style="position: absolute;z-index: 2;' . $this->_item->getStyle() .'">'. $this->getLabel() .'</div>';
        $html .= '</div>';
        $this->_html = $html;
        return $this->_html;
    }
    /**
     * Initialize Helper to work with Item
     *
     * @param \NVT\BannerManagement\Model\Item $item
     * @return $this
     */
    public function init($item = null, $class = null)
    {
        if(empty($item)){
            $item = $this->getCurrentItem();
        }
        $this->_reset();
        $this->setItem($item);
        $this->setClass($class);
        $this->setImageProperties();
        return $this;
    }

    /**
     * Set current Item
     *
     * @param \NVT\BannerManagement\Model\Item $item
     * @return $this
     */
    protected function setItem($item)
    {
        $this->_item = $item;
        return $this;
    }
    protected function setClass($class)
    {
        if(is_array($class)){
            $rclass = implode(' ', $class);
        }
        else {
            $rclass = $class;
        }
        $rclass .= ' item-wrapper item-'. $this->_item->getId();
        $this->_class = trim($rclass);
    }
    public function getClass()
    {
        return $this->_class;
    }
    /**
     * Set image properties
     *
     * @return $this
     */
    protected function setImageProperties()
    {
        $html  = '<div class="'. $this->getClass() .'" style="position: relative;z-index: 1;" >';
        $html .= '  <img src="'. $this->getImageSrc() .'" alt="'. $this->getAlt() .'" />';
        $html .= '  <div class="caption" style="position: absolute;z-index: 2;' . $this->_item->getStyle() .'">'. $this->getLabel() .'</div>';
        $html .= '</div>';
        $this->_html = $html;
        return $this;
    }
    public function toHtml()
    {
        return $this->_html;
    }

    public function _init($item)
    {
        $this->_reset();
        $this->setItem($item);
        $this->wrapperItem();
        return $this;
    }
    public function wrapperItem()
    {
        $labels = $this->getStyle();
        $html  = '<div class="__wrapper" style="position: relative;z-index: 1;" >';
        $html .= '    <div data-role="header" ><div data-role="toolbar"><span class="__icon-add" data-role="add"></span><span class="__icon-edit" data-role="edit"></span><span class="__icon-remove" data-role="remove"></span></div></div>';
        $html .= '    <div data-role="content" >';
        if(is_array($labels) && count($labels)){
            foreach ($labels as $label){
                $html .= $this->addWidget($label);
            }
        }
        $html .= '    </div>';
        if($this->_item->getId()){
            $html .= '  <img src="'. $this->getImageSrc() .'" alt="'. $this->getAlt() .'" />';
        }
        $html .= '</div>';
        $html .= $this->editContent();
        $html .= '<script>require(["jquery", "jquery/ui", "jquery/colorpicker/js/colorpicker", "domReady!"], function($){';
        // add widget
        $html .= '$("body").on("click", ".__wrapper > [data-role=\"header\"] [data-role=\"toolbar\"] [data-role=\"add\"]", function(){';
        $html .= '    var elementContent = $(this).parent().parent().next("[data-role=\"content\"]");';
        $html .= '    var item = \''. $this->addWidget() . '\';';
        $html .= '    elementContent.append(item);';
        $html .= '    changeStyle();';
        $html .= '    $(item).attr("style","position: absolute; top: 78px; left: 228px; width: 214px; height: 130px;");';
        // resizebel
        $html .= '    $(".__wrapper").find("[data-role=\"widget\"]").resizable({animate: true});';
        // draggable widget
        $html .= '    if($(".__wrapper").find("[data-role=\"widget\"]").length){
                        drag();
                      }';
        $html .= '});';
        // edit widget open popup
        $html .= '$("body").on("click", ".__wrapper [data-role=\"widget\"] [data-role=\"edit\"]", function(){
                    var popup = $("body").find(".__wrapper_edit_widget");
                    var selection = $(this).parent().parent().parent();
                    var style = selection.find("[data-role=\"body\"]");
                    var color = rgb2hex(style.css("color"));
                    selection.find("#style_color").val(color); 
                    selection.find("#style_font_size").val(style.css("font-size"));     
                    var content = selection.find("[data-role=\"body\"]").html()
                        $(".__wrapper_edit_widget").find("#style_text").val($.trim(content));
                    var wWin    = window.innerWidth;
                    var hWin    = window.innerHeight;
                    var top = (hWin - popup.height())/2;
                    var left    = (wWin - popup.width())/2;
                        popup.css({"position":"absolute", "top":top, "left":left});
                        popup.addClass("__show");
                        popup.next(".__wrapper_edit_widget_bg").addClass("__bg_show");
                        selectionUpdate(selection.index());
                        
                 });';
        // save change popup
        $html .= '$("body").on("click", ".__wrapper_edit_widget [data-role=\"change\"]", function(){
                    var selector = $(this).attr("data-selector");
                    var text = $(".__wrapper_edit_widget #style_text").val();
                    var font_size = $(".__wrapper_edit_widget #style_font_size").val();
                    var color = $(".__wrapper_edit_widget #style_color").val();
                    if (typeof selector === "undefined") {
                        alert("please pick an item.");
                    }
                    else{
                        if(confirm("Are you sure you want to change item!")){
                            var element = $(".__wrapper "+ selector).find("[data-role=\"body\"]");
                            element.html(text);
                            element.css({"font-size": font_size, "color": color});
                            
                        }
                        changeStyle();
                    }  
                     $(".__wrapper_edit_widget_bg").trigger("click");   
                 });';

        // close popup
        $html .= '$("body").on("click", ".__wrapper_edit_widget_bg.__bg_show", function(){
                    var popup = $(this).prev(".__wrapper_edit_widget");
                        popup.removeClass("__show");
                        $(this).removeClass("__bg_show");
                 });';




        // remove widget
        $html .= '$("body").on("click", ".__wrapper [data-role=\"widget\"] [data-role=\"remove\"]", function(){
                     var elementWidget = $(this).parent().parent().parent("[data-role=\"widget\"]");
                     if(confirm("Are you sure you want to delete item!")){
                        elementWidget.remove();
                        changeStyle();
                     }
                 });';
        // remove widget by selector
        $html .= '$("body").on("click", ".__wrapper > [data-role=\"header\"] [data-role=\"toolbar\"] [data-role=\"remove\"]", function(){
                    var selector = $(this).attr("data-selector");
                    if (typeof selector === "undefined") {
                        alert("please pick an item.");
                    }
                    else{
                        if(confirm("Are you sure you want to delete item!")){
                            $(".__wrapper "+ selector).remove();
                        }
                    }
                 });';



        // click change selector
        $html .= '$("body").on("click", ".__wrapper [data-role=\"widget\"]", function(){
                    selectionUpdate($(this).index());
                 });';

        // function add sellection
        $html .= 'function selectionUpdate(idx){
                  var selector = "[data-role=\"widget\"]:eq("+ idx +")";
                  var element = $(".__wrapper > [data-role=\"header\"] [data-role=\"toolbar\"]");
                  var eEdit = element.find("[data-role=\"edit\"]");
                  var eRemove = element.find("[data-role=\"remove\"]");
                      eEdit.attr("data-selector", selector);
                      eRemove.attr("data-selector", selector);
                  var editPopup = $(".__wrapper_edit_widget");  
                      editPopup.find("[data-role=\"change\"]").attr("data-selector", selector);
                  var color = $(selector).css("color");
                      editPopup.find("[name=\"style[color]\"]").val(color);
                  var size = $(selector).css("font-size");
                      editPopup.find("[name=\"style[font_size]\"]").val(size);                      
                      changeStyle();
                 }';
        // add color picker
//        $html .= 'var $el = $("body").find("#style_color");
//                    $el.ColorPicker({
//                        onChange: function (hsb, hex, rgb) {
//                        $el.css("backgroundColor", "#" + hex).val("#" + hex);
//                    }
//                  });';




        // save style
        $html .= 'function changeStyle(){
                    var data = [];
                    $("[name=\"style[widget][data]\"]").each(function(index, element){
                        var content = $(this).val();
                        if(content.length){
                            data[index] =  content.replace( /\"/g, "\'" );
                        }
                    });
                    $("[name=\"item[design]\"]").val("["+ data.join(",") +"]");
                    drag();
                 }';









        //Function to convert hex format to a rgb color
        $html .= 'function rgb2hex(orig){
                    var rgb = orig.replace(/\s/g,\'\').match(/^rgba?\((\d+),(\d+),(\d+)/i);
                    return (rgb && rgb.length === 4) ? "#" +
                           ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
                           ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
                           ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
                 }';


        //Function draggable
        $html .= 'function drag(){
                    $(".__wrapper").find("[data-role=\"widget\"]").draggable({ containment: ".__wrapper", cancel: "[data-role=\"body\"]", scroll: false, stop: function() {
                        var element = $(".__wrapper");
                        var wPosition = element.position();
                        var height = element.height() - wPosition.top - parseFloat($(this).css("borderTopWidth")) - parseFloat($(this).css("borderBottomWidth"));
                        var width = element.width() - wPosition.left  - parseFloat($(this).css("borderLeftWidth")) - parseFloat($(this).css("borderRightWidth"));
                        var position = $(this).position();
                        var top = (position.top * 100)/height;
                        var left = (position.left * 100)/width;
                        var w = $(this).find("[data-role=\"body\"]").width();
                        var h = $(this).find("[data-role=\"body\"]").height();
                        var style = "top: "+ top.toFixed(3) +"%;left: "+ left.toFixed(3) +"%; width: "+ w +"px;height: "+ (h + 10) +"px;";
                        var color = $(this).find("[data-role=\"body\"]").css("color");
                        var fontSize = $(this).find("[data-role=\"body\"]").css("font-size");
                        style += "color: "+ color +"; font-size: "+ fontSize +";";
                        
                        var text = $(this).find("[data-role=\"body\"]").html();
                        var data = "{\"text\": \""+ $.trim(text) +"\", \"style\": \""+ style +"\"}"; 
                        $(this).find("input").val(data);
                        selectionUpdate($(this).index());
                      } 
                    });
        }
        drag();';

        $html .= '});</script>';

        $this->_html = $html;
    }

    public function addWidget($data = null)
    {
        $html  = '';
        if(is_object($data)){
            $label = $data;
        }
        else{
            $label = new \stdClass;
            $label->text = __('Click here to change the content.');
            $label->style = null;
        }
        $html .= '    <div data-role="widget" style="'. $label->style .'">';
        $html .= '        <div data-role="header" ><input type="hidden" name="style[widget][data]"><div data-role="toolbar"><span class="__icon-add" title="Edit" data-role="edit"></span><span class="__icon-remove" title="Remove" data-role="remove"></span></div></div>';
        $html .= '        <div data-role="body">';
        $html .=            $label->text;
        $html .= '        </div>';
        $html .= '    </div>';
        return $html;
    }
    public function editContent()
    {
        $html  = '';
        $html .= '<div class="__wrapper_edit_widget">
                        <div id="dialog-form" title="Edit content">
                          <p class="validateTips">Enter the text to edit.</p>
                            <fieldset>
                              <label for="style_text">Text</label>
                              <textarea id="style_text" name="style[text]" rows="2" cols="15" class="textarea admin__control-textarea"></textarea>
                              <div style="clear: both;"></div>
                              <div class="style_list">
                                  <label for="style_font_size">Font size</label>
                                  <input type="text" name="style[font_size]" id="style_font_size" class="input-text admin__control-text">
                                  <label for="style_color">Color</label>
                                  <input type="text" name="style[color]" id="style_color" class="input-text admin__control-text">
                              </div>
                              <hr style="clear: both;" />
                              <button type="button" data-role="change" class="__button_save">Save Changes</button>
                            </fieldset>
                        </div>
                  </div>
                  <div class="__wrapper_edit_widget_bg"></div>
                  ';


        return $html;
    }
    /**
     * @return string
     */
    public function getImageSrc()
    {
        return $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, false)
        . $this->_item->getImage();
    }

    /**
     * Get current Image model
     *
     * @return \NVT\BannerManagement\Model\ItemFactory
     */
    protected function _getModel()
    {
        if (!$this->_model) {
            $this->_model = $this->_itemFactory->create();
        }
        return $this->_model;
    }


    /**
     * Get current Item
     *
     * @return \NVT\BannerManagement\Model\Item
     */
    protected function getItem()
    {
        return $this->_item;
    }

    /**
     * Set Image file
     *
     * @param string $file
     * @return $this
     */
    public function setImageFile($file)
    {
        $this->_imageFile = $file;
        return $this;
    }

    /**
     * Get Image file
     *
     * @return string
     */
    protected function getImageFile()
    {
        return $this->_imageFile;
    }
    /**
     * Return image label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_item->getDescription();
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->_item->getTitle();
    }

    /**
     * @return mixed
     */
    public function getStyle()
    {
        $s = str_replace('\'','"', $this->_item->getDesign());
        return json_decode($s);
    }
    public function getDesign()
    {
        $item = str_replace('\'','"', $this->_item->getDesign());
        return json_decode($item);
    }
}
