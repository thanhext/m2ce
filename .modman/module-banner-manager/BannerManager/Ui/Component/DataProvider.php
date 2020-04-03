<?php
/**
 * Copyright © Thomas Nguyen, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace T2N\BannerManager\Ui\Component;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use T2N\BannerManager\Model\ResourceModel\Banner\Item\CollectionFactory;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

/**
 * DataProvider for cms ui.
 */
class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return \T2N\BannerManager\Model\ResourceModel\Banner\Item\Collection
     */
    protected function getCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $collection    = $this->getCollection();
        $data['items'] = [];
        if ($this->request->getParam('banner_id')) {
            $collection->addFieldToFilter('banner_id', $this->request->getParam('banner_id'));
            $data = $collection->toArray();
        }

        return $data;
    }
}
