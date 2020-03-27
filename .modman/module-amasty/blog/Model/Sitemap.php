<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model;

/**
 * Class Sitemap
 */
class Sitemap extends \Magento\Framework\Model\AbstractModel
{
    const AMBLOG_TYPE_BLOG = 'blog';

    const AMBLOG_TYPE_POST = 'post';

    const AMBLOG_TYPE_CATEGORY = 'category';

    const AMBLOG_TYPE_TAG = 'tag';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * @var \Amasty\Blog\Helper\Url
     */
    private $urlHelper;

    /**
     * @var \Amasty\Blog\Api\PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var \Amasty\Blog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var \Amasty\Blog\Api\TagRepositoryInterface
     */
    private $tagRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    private $settingsHelper;

    protected function _construct()
    {
        parent::_construct();
        $this->dateTime = $this->getData('date_time');
        $this->urlHelper = $this->getData('url_helper');
        $this->postRepository = $this->getData('post_repository');
        $this->categoryRepository = $this->getData('category_repository');
        $this->tagRepository = $this->getData('tag_repository');
        $this->storeManager = $this->getData('store_manager');
        $this->settingsHelper = $this->getData('settings_helper');
    }

    /**
     * @param null $storeId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function generateLinks($storeId = null)
    {
        $storeId = $storeId ? : $this->storeManager->getStore()->getId();

        $links = [];
        $currentDate = $this->dateTime->gmtDate('Y-m-d');
        $links[] = ['url' => $this->urlHelper->getUrl(), 'date' => $currentDate];

        $posts = $this->postRepository->getActivePosts($storeId)->setDateOrder();
        foreach ($posts as $post) {
            $post->setStoreId($storeId);
            $links[] = ['url' => $post->getPostUrl(), 'date' => $currentDate];
        }

        $categories = $this->categoryRepository->getActiveCategories($storeId)->setSortOrder('asc');
        foreach ($categories as $category) {
            $category->setStoreId($storeId);
            $links[] = ['url' => $category->getUrl(), 'date' => $currentDate];
        }

        $tags = $this->tagRepository->getActiveTags($storeId)
            ->setMinimalPostCountFilter($this->settingsHelper->getTagsMinimalPostCount())
            ->setNameOrder();

        foreach ($tags as $tag) {
            $tag->setStoreId($storeId);
            $links[] = ['url' => $tag->getTagUrl(), 'date' => $currentDate];
        }

        return $links;
    }
}
