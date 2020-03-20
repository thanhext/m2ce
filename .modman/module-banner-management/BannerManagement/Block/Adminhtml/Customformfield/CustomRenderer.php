<?php
namespace NVT\BannerManagement\Block\Adminhtml\Customformfield;
/**
 * Class CustomRenderer
 * @package NVT\BannerManagement\Block\Adminhtml\Customformfield
 */
class CustomRenderer extends \Magento\Framework\Data\Form\Element\AbstractElement
{

    public function getNumberField()
    {
        return ($this->getNfield() ? $this->getNfield() : 3);
    }
    /**
     * Get the Html for the element.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = '';
        $htmlId = $this->getHtmlId();

        $beforeElementHtml = $this->getBeforeElementHtml();
        if ($beforeElementHtml) {
            $html .= '<label class="addbefore" for="' . $htmlId . '">' . $beforeElementHtml . '</label>';
        }
        for ($i = 1;$i <= $this->getNumberField(); $i++){
            $html .= '<input id="' . $htmlId . '" name="' . $this->getName() . '" ' . $this->_getUiId() . ' value="' .
            $this->getEscapedValue() . '" ' . $this->serialize($this->getHtmlAttributes()) . '/>';

        }

        $this->setType('hidden');
        $html .= '<input id="' . $htmlId . '" name="' . $this->getName() . '" ' . $this->_getUiId() . ' value="' .
            $this->getEscapedValue() . '" ' . $this->serialize($this->getHtmlAttributes()) . '/>';

        $afterElementJs = $this->getAfterElementJs();
        if ($afterElementJs) {
            $html .= $afterElementJs;
        }

        $afterElementHtml = $this->getAfterElementHtml();
        if ($afterElementHtml) {
            $html .= '<label class="addafter" for="' . $htmlId . '">' . $afterElementHtml . '</label>';
        }

        return $html;
    }
    /**
     * Get the after element html.
     *
     * @return mixed
     */
    public function getAfterElementHtml()
    {
        $customDiv = '<div style="width:600px;height:200px;margin:10px 0;border:2px solid #000" class="custom-div">
                        

                    </div>';
        return $customDiv;
    }
}