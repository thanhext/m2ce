<?php

namespace T2N\BannerManager\Controller\Adminhtml\Index;

use T2N\BannerManager\Model\BannerFactory;
use T2N\BannerManager\Model\BannerRepository;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use T2N\BannerManager\Api\Data\BannerInterface as BN;
use Magento\Framework\View\Result\PageFactory;
use T2N\BannerManager\Model\System\Config\Status;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Save
 */
class Save extends Banner
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var BannerRepository
     */
    protected $bannerRepository;

    /**
     * Save constructor.
     *
     * @param Context                $context
     * @param BannerFactory          $bannerFactory
     * @param BannerRepository       $bannerRepository
     * @param DataPersistorInterface $dataPersistor
     * @param PageFactory            $resultPageFactory
     */
    public function __construct(
        Context $context,
        BannerFactory $bannerFactory,
        BannerRepository $bannerRepository,
        DataPersistorInterface $dataPersistor,
        ScopeConfigInterface $scopeConfig,
        PageFactory $resultPageFactory
    ) {
        $this->scopeConfig      = $scopeConfig;
        $this->bannerRepository = $bannerRepository;
        parent::__construct($context, $bannerFactory, $dataPersistor, $resultPageFactory);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        //$data          = $this->getRequest()->getPostValue();
        $banner        = $this->getRequest()->getParam(BN::FORM_GENERAL);
        $bannerOptions = $this->getRequest()->getParam(BN::FORM_OPTIONS);
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($banner) {
            $banner['options'] = $this->prepareOptions();
            if (isset($banner[BN::IS_ACTIVE]) && $banner[BN::IS_ACTIVE] === 'true') {
                $banner[BN::IS_ACTIVE] = Status::STATUS_ENABLED;
            }

            if (empty($banner[BN::BANNER_ID])) {
                $banner[BN::BANNER_ID] = null;
            }

            /** @var \T2N\BannerManager\Model\Banner $model */
            $model = $this->_bannerFactory->create();
            $id    = $this->getRequest()->getParam(BN::BANNER_ID);
            if ($id) {
                $model = $this->bannerRepository->getById($id);
            }

            $model->setData($banner);
            try {
                $this->bannerRepository->save($model);
                $this->messageManager->addSuccess(__('You saved the thing.'));
                $this->dataPersistor->clear('banner_entity');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', [BN::BANNER_ID => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('banner_entity', $banner);
            return $resultRedirect->setPath('*/*/edit', [BN::BANNER_ID => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return array
     */
    protected function prepareOptions()
    {
        $options        = [];
        $bannerOptions  = $this->getRequest()->getParam(BN::FORM_OPTIONS);
        $defaultOptions = $this->scopeConfig->getValue('t2n/banner/options');
        foreach ($bannerOptions as $optionKey => $optionValue) {
            if (isset($defaultOptions[$optionKey]) && $defaultOptions[$optionKey] != $optionValue) {
                $options[$optionKey] = $optionValue;
            }
        }

        return $options;
    }
}
