<?php

namespace T2N\BannerManager\Controller\Adminhtml\Index;

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
