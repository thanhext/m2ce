<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin\Model;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote;

/**
 * Class QuotePlugin
 */
class QuotePlugin
{
    /**
     * @var AddressInterface
     */
    private $address;

    /**
     * @param Quote $subject
     * @param AddressInterface|null $address
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @codingStandardsIgnoreStart
     */
    public function beforeSetShippingAddress(Quote $subject, AddressInterface $address = null)
    {
        $this->address = $address;
    }

    /**
     * @param Quote $subject
     * @param Quote $result
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSetShippingAddress(Quote $subject, Quote $result)
    {
        if ($this->address && !$this->address->getRegionId()) {
            $subject->getShippingAddress()->setRegionId(null);
        }
    }
}
