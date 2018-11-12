<?php
namespace AstralWeb\Banner\Api;

use AstralWeb\Banner\Api\Data\BannerInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface BannerRepositoryInterface 
{
    public function save(BannerInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(BannerInterface $page);

    public function deleteById($id);
}
