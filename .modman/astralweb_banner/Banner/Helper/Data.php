<?php
namespace AstralWeb\Banner\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\DesignInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Cms\Model\PageFactory;


/**
 * Class Data
 * @package AstralWeb\Banner\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_IMAGE      = 'banner';

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * Data constructor.
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $categoryCollectionFactory
     * @param PageFactory $pageFactory
     * @param Context $context
     */
    public function __construct(
       StoreManagerInterface $storeManager,
       CollectionFactory $categoryCollectionFactory,
       PageFactory $pageFactory,
       Context $context
    ) {
        $this->storeManager = $storeManager;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * @param $path
     * @param string $scopeType
     * @param null $scopeCode
     * @return mixed
     */
    public function getConfig($path, $scopeType = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
       return $this->scopeConfig->getValue($path, $scopeType, $scopeCode);
    }

    /**
     * @return array
     */
    public function getAllCmsPages()
    {
        $page = $this->pageFactory->create();
        $cmspage = [];
        foreach ($page->getCollection() as $item) {
            if ($item->getIdentifier() == 'home') {
                $prefix = 'cms_index_index_id_';
            } else {
                $prefix = 'cms_page_view_id_';
            }
            $cmspage[] = [
                'value' => $prefix.$item->getIdentifier(),
                'label' => $item->getTitle(),
            ];
        }
        return $cmspage;
    }

    /**
     * @return array
     */
    public function getCategoriesArray()
    {
        $categories = [];
        try {
            $categoriesArray = $this->categoryCollectionFactory->create()
                ->addAttributeToSelect('name')
                ->addAttributeToSort('path', 'asc')
                ->load()
                ->toArray();

        } catch (\Magento\Framework\Exception\LocalizedException $localizedException) {
            $categoriesArray = [];
        }

        if (count($categoriesArray)){
            foreach ($categoriesArray as $categoryId => $category) {
                if (isset($category['name']) && isset($category['level'])) {
                    $categories[] = [
                        'label' => $category['name'],
                        'value' => 'catalog_category_view_id_'.$categoryId,
                    ];
                }
            }
        }

        return $categories;
    }

    /**
     * @return array
     */
    public function getSeachresult()
    {

        $categories = array();
        $categories[] = [
            'label' => 'Search Result',
            'value' => 'catalogsearch_result_index',
        ];
        return $categories;
    }

    /**
     * @return array
     */
    public function getAllPagesOptions()
    {
        $options[] = [
            'label' => 'Categories',
            'value' => $this->getCategoriesArray(),
        ];
        $options[] = [
            'label' => 'CMS',
            'value' => $this->getAllCmsPages(),
        ];
        $options[] =[
            'label' => 'Search Result',
            'value' => $this->getSeachresult(),
        ];

        return $options;
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }

    /**
     * @return mixed
     */
    public function getThemeId()
    {
        return $this->getConfig(
            DesignInterface::XML_PATH_THEME_ID,
            ScopeInterface::SCOPE_STORE,
            $this->getStore()->getId()
        );
    }

}
