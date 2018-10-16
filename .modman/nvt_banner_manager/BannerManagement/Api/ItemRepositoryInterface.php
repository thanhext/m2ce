<?php
namespace NVT\BannerManagement\Api;

use NVT\BannerManagement\Api\Data\ItemInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ItemRepositoryInterface 
{
    public function save(ItemInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(ItemInterface $page);

    public function deleteById($id);
}
