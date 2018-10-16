<?php
namespace NVT\MenuManagement\Controller\Adminhtml\Item;
/**
 * Class Save
 * @package NVT\MenuManagement\Controller\Adminhtml\Item
 */
class Save extends \Magento\Backend\App\Action
{

    protected $_itemFactory;
    protected $_coreRegistry;
    protected $_resultPageFactory;
    protected $_fileSystem;
    protected $_uploaderFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \NVT\MenuManagement\Model\ItemFactory $itemFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->_fileSystem          = $fileSystem;
        $this->_uploaderFactory     = $uploaderFactory;
        $this->_itemFactory         = $itemFactory;
        $this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;
        parent::__construct($context);
    }
    
    
    public function execute()
    {
        $cacheTag   = \NVT\MenuManagement\Model\Item::CACHE_TAG;
        $data       = $this->getRequest()->getParam($cacheTag);
        $isPost     = $this->getRequest()->getPost();
        $identities = $cacheTag . '_id';
        if ($isPost) {
            $model = $this->_itemFactory->create();
//            $model->setData($data);
            /*
            * Save image upload
            */
            if (isset($_FILES['image']) && isset($_FILES['image']['name']) && strlen($_FILES['image']['name'])) {
                try {
                    $basePath = \NVT\MenuManagement\Helper\Item::BANNER_PATH_CONFIG;
                    $uploader = $this->_uploaderFactory->create(['fileId' => 'image']);
                    $uploader->setAllowedExtensions(['jpg','png','gif']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $mediaDirectory = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath($basePath)
                    );
                    $data['image'] = $basePath.$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                        $this->_redirect('*/*/edit', [$identities => $model->getData($identities), '_current' => true]);
                        return;
                    }
                }
            } else {
                if (isset($data['image']) && isset($data['image']['value'])) {
                    if (isset($data['image']['delete'])) {
                        $data['image'] = null;
                        $data['delete_image'] = true;
                    } elseif (isset($data['image']['value'])) {
                        $data['image'] = $data['image']['value'];
                    } else {
                        $data['image'] = null;
                    }
                }
            }
            $model->setData($data);
            try {
                // Save news
                $model->save();
                // Display success message
                $this->messageManager->addSuccess(__('The Item has been saved.'));
                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', [$identities => $model->getData($identities), '_current' => true]);
                    return;
                }
                // Go to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/index', [$identities => $model->getData($identities)]);
        }
    }

}
