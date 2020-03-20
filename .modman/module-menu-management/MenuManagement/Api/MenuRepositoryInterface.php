<?php
namespace NVT\MenuManagement\Api;

use NVT\MenuManagement\Api\Data\MenuInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface MenuRepositoryInterface 
{
    public function save(MenuInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(MenuInterface $page);

    public function deleteById($id);
}
