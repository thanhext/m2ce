<?php
namespace AstralWeb\Banner\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\MediaStorage\Model\File\Uploader as FileUploader;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

/**
 * Class Image
 * @package AstralWeb\Banner\Helper
 */
class Image extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_IMAGE      = 'banner';

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     * @since 101.0.0
     */
    protected $fileStorageDb;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     * @since 101.0.0
     */
    protected $mediaDirectory;

    /**
     * Data constructor.
     * @param Context $context
     */
   public function __construct(
       StoreManagerInterface $storeManager,
       Database $fileStorageDb,
       Filesystem $filesystem,
       Context $context
   ) {
       parent::__construct($context);
       $this->storeManager = $storeManager;
       $this->fileStorageDb = $fileStorageDb;
       $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
   }

    /**
     * @param $path
     * @return mixed
     */
   public function getConfig($path)
   {
       return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
   }

    /**
     * @param string $file
     * @return string
     */
    protected function _prepareFile($file)
    {
        return ltrim(str_replace('\\', '/', $file), '/');
    }

    /**
     * @return string
     */
    public function getBaseMediaPathAddition()
    {
        return self::XML_PATH_IMAGE;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getMediaPath($file)
    {
        return $this->getBaseMediaPathAddition() . '/' . $this->_prepareFile($file);
    }

    /**
     * @param $file
     * @return string
     */
    public function getMediaUrl($file)
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
        return $mediaUrl . $this->getMediaPath($file);
    }
}
