<?php

namespace T2N\BannerManager\Controller\Adminhtml\Item;

/**
 * Class NewAction
 */
class NewAction extends BannerItem
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
