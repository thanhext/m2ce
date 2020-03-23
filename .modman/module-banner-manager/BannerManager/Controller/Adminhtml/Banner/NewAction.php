<?php

namespace T2N\BannerManager\Controller\Adminhtml\Banner;

use T2N\BannerManager\Controller\Adminhtml\Banner;

/**
 * Class NewAction
 */
class NewAction extends Banner
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
