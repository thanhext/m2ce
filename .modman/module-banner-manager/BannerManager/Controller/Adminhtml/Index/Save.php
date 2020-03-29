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
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use T2N\BannerManager\Model\System\Config\Status;

/**
 * Class Save
 */
class Save extends Banner
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var BannerRepository
     */
    protected $bannerRepository;

    public function __construct(
        Context $context,
        BannerFactory $bannerFactory,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        DataPersistorInterface $dataPersistor,
        BannerRepository $bannerRepository
    ) {
        $this->dataPersistor     = $dataPersistor;
        $this->bannerRepository = $bannerRepository;
        parent::__construct($context, $bannerFactory, $coreRegistry, $resultPageFactory);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $banner = $this->getRequest()->getParam('banner');
        $bannerOptions = $this->getRequest()->getParam('options');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($banner) {
            $banner['options'] = $bannerOptions;
            if (isset($banner['is_active']) && $banner['is_active'] === 'true') {
                $banner['is_active'] = Status::STATUS_ENABLED;
            }

            if (empty($banner['entity_id'])) {
                $banner['entity_id'] = null;
            }

            /** @var \T2N\BannerManager\Model\Banner $model */
            $model = $this->_bannerFactory->create();
            $id    = $this->getRequest()->getParam('id');
            if ($id) {
                $model = $this->bannerRepository->getById($id);
            }

            $model->setData($banner);
            try {
                $this->bannerRepository->save($model);
                $this->messageManager->addSuccess(__('You saved the thing.'));
                $this->dataPersistor->clear('banner_entity');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('banner_entity', $banner);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
