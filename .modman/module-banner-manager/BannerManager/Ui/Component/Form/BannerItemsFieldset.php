<?php
/**
 * Copyright © Thomas Nguyen, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace T2N\BannerManager\Ui\Component\Form;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\ComponentVisibilityInterface;

/**
 * Class BannerItemsFieldset
 *
 * @package T2N\BannerManager\Ui\Component\Form
 */
class BannerItemsFieldset extends \Magento\Ui\Component\Form\Fieldset implements ComponentVisibilityInterface
{
    /**
     * @param ContextInterface $context
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        $this->context = $context;

        parent::__construct($context, $components, $data);
    }

    /**
     * Can show banner items tab in tabs or not
     *
     * Will return false for not created banned in a case when admin user created new banner.
     * Needed to hide banner items tab from create new banner page
     *
     * @return boolean
     */
    public function isComponentVisible(): bool
    {
        $bannerId = $this->context->getRequestParam('id');

        return (bool)$bannerId;
    }
}
