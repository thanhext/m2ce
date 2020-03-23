<?php

namespace T2N\SuggestProducts\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use T2N\SuggestProducts\Helper\Data;

/**
 * Class Products
 */
class Products extends AbstractProduct
{
    /**
     * @var Data
     */
    protected $helperData;
    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = Toolbar::class;

    /**
     * Product Collection
     *
     * @var AbstractCollection
     */
    protected $_productCollection;

    /**
     * Catalog layer
     *
     * @var Layer
     */
    protected $_catalogLayer;

    /**
     * @var PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param Context                            $context
     * @param PostHelper                         $postDataHelper
     * @param Resolver                           $layerResolver
     * @param CategoryRepositoryInterface        $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array                              $data
     */
    public function __construct(
        Context $context,
        Data $helperData,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = []
    ) {
        $this->helperData         = $helperData;
        $this->_catalogLayer      = $layerResolver->get();
        $this->_postDataHelper    = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper          = $urlHelper;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return (int)$this->helperData->getCategoryId();
    }

    /**
     * @return \Magento\Catalog\Api\Data\CategoryInterface
     * @throws NoSuchEntityException
     */
    public function getCategory()
    {
        $categoryId = $this->getCategoryId();
        return $this->categoryRepository->get($categoryId);
    }

    /**
     * Configures product collection from a layer and returns its instance.
     *
     * Also in the scope of a product collection configuration, this method initiates configuration of Toolbar.
     * The reason to do this is because we have a bunch of legacy code
     * where Toolbar configures several options of a collection and therefore this block depends on the Toolbar.
     *
     * This dependency leads to a situation where Toolbar sometimes called to configure a product collection,
     * and sometimes not.
     *
     * To unify this behavior and prevent potential bugs this dependency is explicitly called
     * when product collection initialized.
     *
     * @return Collection
     */
    private function initializeProductCollection()
    {
        $layer = $this->getLayer();
        $origCategory = null;
        if ($this->getCategoryId()) {
            try {
                $category = $this->categoryRepository->get($this->getCategoryId());
            } catch (NoSuchEntityException $e) {
                $category = null;
            }

            if ($category) {
                $origCategory = $layer->getCurrentCategory();
                $layer->setCurrentCategory($category);
            }
        }
        $collection = $layer->getProductCollection();
        $collection->addCategoryFilter($category);

        if ($origCategory) {
            $layer->setCurrentCategory($origCategory);
        }

        $this->_eventManager->dispatch(
            'catalog_block_product_suggest_collection',
            ['collection' => $collection]
        );

        return $collection;
    }
}
