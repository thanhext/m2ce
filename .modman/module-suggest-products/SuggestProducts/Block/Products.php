<?php

namespace T2N\SuggestProducts\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use T2N\SuggestProducts\Helper\Data;

/**
 * Class Products
 */
class Products extends AbstractProduct implements IdentityInterface
{
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;
    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Products constructor.
     *
     * @param CollectionFactory $productCollectionFactory
     * @param CategoryFactory   $categoryFactory
     * @param Template\Context  $context
     * @param array             $data
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        CategoryFactory $categoryFactory,
        Template\Context $context,
        Data $helperData,
        array $data = []
    ) {
        $this->helperData               = $helperData;
        $this->categoryFactory          = $categoryFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities = [];
        if ($category = $this->getCategory()) {
            $collection   = $this->getProductCollection();
            $identities[] = Category::CACHE_TAG . '_' . $category->getId();
            foreach ($collection as $product) {
                $identities[] = Product::CACHE_TAG . '_' . $product->getId();
            }
        }

        return $identities;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        $categoryId = $this->helperData->getCategoryId();
        return $this->categoryFactory->create()->load($categoryId);
    }

    /**
     * @return Collection
     */
    public function getProductCollection()
    {
        $category   = $this->getCategory();
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoryFilter($category);
        $collection->addAttributeToFilter('visibility', Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status', Status::STATUS_ENABLED);
        return $collection;
    }
}
