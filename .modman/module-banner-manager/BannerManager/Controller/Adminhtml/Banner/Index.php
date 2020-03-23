<?php

namespace T2N\BannerManager\Controller\Adminhtml\Banner;

use T2N\BannerManager\Controller\Adminhtml\Banner;

/**
 * Class Index
 */
class Index extends Banner
{
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Banner Manager'));
        return $resultPage;
    }
}

