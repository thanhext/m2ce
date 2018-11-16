<?php
namespace AstralWeb\Banner\Api;

use AstralWeb\Banner\Api\Data\ItemInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ItemRepositoryInterface
 * @package AstralWeb\Banner\Api
 */
interface ItemRepositoryInterface 
{
    public function save(ItemInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(ItemInterface $page);

    public function deleteById($id);
}
