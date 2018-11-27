<?php
namespace AstralWeb\Banner\Controller\Adminhtml\Grid;

use Magento\Theme\Helper\Storage;
/**
 * Class NewAction
 * @package AstralWeb\Banner\Controller\Adminhtml\Grid
 */
class NewAction extends \AstralWeb\Banner\Controller\Adminhtml\Banner
{
    public function execute()
    {
        $this->_forward('edit', null, null, [Storage::PARAM_THEME_ID => $this->getThemeId()]);
    }
}