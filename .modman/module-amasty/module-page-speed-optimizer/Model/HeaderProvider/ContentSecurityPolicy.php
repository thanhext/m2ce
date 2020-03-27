<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_PageSpeedOptimizer
 */


namespace Amasty\PageSpeedOptimizer\Model\HeaderProvider;

use Magento\Framework\App\Response\HeaderProvider\AbstractHeaderProvider;

class ContentSecurityPolicy extends AbstractHeaderProvider
{
    /**
     * @var IsSetXFrameOptions
     */
    private $isSetXFrameOptions;

    public function __construct(
        IsSetXFrameOptions $isSetXFrameOptions
    ) {
        $this->isSetXFrameOptions = $isSetXFrameOptions;
    }

    public function canApply()
    {
        return $this->isSetXFrameOptions->isSetHeader();
    }

    public function getName()
    {
        return 'Content-Security-Policy';
    }

    public function getValue()
    {
        return 'frame-ancestors ' . $this->isSetXFrameOptions->getBaseUrl();
    }
}
