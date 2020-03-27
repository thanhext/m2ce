<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ElasticSearch
 */


namespace Amasty\ElasticSearch\Controller\Adminhtml\RelevanceRule;

class NewAction extends AbstractRelevance
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
