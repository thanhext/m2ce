<?php

namespace T2N\BannerManager\Block\Banner\Item;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

/**
 * Class Banner Item Edit
 */
class Edit extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \T2N\BannerManager\Api\Data\ItemInterface|null
     */
    protected $_item = null;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \T2N\BannerManager\Api\BannerItemRepositoryInterface
     */
    protected $_itemRepository;

    /**
     * @var \T2N\BannerManager\Api\Data\ItemInterfaceFactory
     */
    protected $itemDataFactory;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context,
        \T2N\BannerManager\Api\BannerItemRepositoryInterface $bannerItemRepository,
        \T2N\BannerManager\Api\Data\ItemInterfaceFactory $itemDataFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->_itemRepository  = $bannerItemRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->itemDataFactory  = $itemDataFactory;
        parent::__construct($context, $data);
    }

    /**
     * Return the associated item.
     *
     * @return \T2N\BannerManager\Api\Data\ItemInterface
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * Return the Url for saving.
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->_urlBuilder->getUrl(
            'banner/item/formPost',
            ['_secure' => true, 'id' => $this->getItem()->getId()]
        );
    }

    /**
     * Initialize item object.
     *
     * @return void
     */
    private function initItemObject()
    {
        // Init item object
        if ($itemId = $this->getRequest()->getParam('id')) {
            try {
                $this->_item = $this->_itemRepository->getById($itemId);
                if ($this->_item->getCustomerId() != $this->_customerSession->getCustomerId()) {
                    $this->_item = null;
                }
            } catch (NoSuchEntityException $e) {
                $this->_item = null;
            }
        }

        if ($this->_item === null || !$this->_item->getId()) {
            $this->_item = $this->itemDataFactory->create();
            $customer    = $this->getCustomer();
            $this->_item->setPrefix($customer->getPrefix());
            $this->_item->setFirstname($customer->getFirstname());
            $this->_item->setMiddlename($customer->getMiddlename());
            $this->_item->setLastname($customer->getLastname());
            $this->_item->setSuffix($customer->getSuffix());
        }
    }

    /**
     * Return the title, either editing an existing address, or adding a new one.
     *
     * @return string
     */
    public function getTitle()
    {
        if ($title = $this->getData('title')) {
            return $title;
        }
        if ($this->getItem()->getId()) {
            $title = __('Edit Banner Item');
        } else {
            $title = __('Add New Banner Item');
        }
        return $title;
    }
    /**
     * Prepare the layout of the item edit block.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->initItemObject();
        $this->pageConfig->getTitle()->set($this->getTitle());
        if ($postedData = $this->_customerSession->getItemFormData(true)) {
            $this->dataObjectHelper->populateWithArray(
                $this->_item,
                $postedData,
                \T2N\BannerManager\Api\Data\ItemInterface::class
            );
        }
        return $this;
    }
}
