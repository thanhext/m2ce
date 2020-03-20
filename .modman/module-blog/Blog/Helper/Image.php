<?php
namespace Ecommage\Blog\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\MediaStorage\Model\File\Uploader as FileUploader;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
/**
 * Class Image
 * @package Ecommage\Blog\Helper
 */
class Image extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_IMAGE      = 'blog/post';

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
     * Filesystem directory path of temporary post images
     * relatively to media folder
     *
     * @return string
     */
    public function getBaseTmpMediaPath()
    {
        return 'tmp/' . $this->getBaseMediaPathAddition();
    }

    /**
     * Filesystem directory path of product images
     * relatively to media folder
     *
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
    public function getTmpMediaUrl($file)
    {
        return $this->getBaseTmpMediaUrl() . '/' . $this->_prepareFile($file);
    }

    /**
     * @return string
     */
    public function getBaseTmpMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . 'tmp/' . $this->getBaseMediaPathAddition();
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
     * Move image from temporary directory to normal
     *
     * @param string $file
     * @return string
     * @since 101.0.0
     */
    public function moveImageFromTmp($file)
    {
        $file = $this->getFilenameFromTmp($this->getSafeFilename($file));
        $destinationFile = $this->getUniqueFileName($file);

        if ($this->fileStorageDb->checkDbUsage()) {
            $this->fileStorageDb->renameFile(
                $this->getTmpMediaShortUrl($file),
                $this->getMediaShortUrl($destinationFile)
            );

            $this->mediaDirectory->delete($this->getTmpMediaPath($file));
            $this->mediaDirectory->delete($this->getMediaPath($destinationFile));
        } else {
            $this->mediaDirectory->renameFile(
                $this->getTmpMediaPath($file),
                $this->getMediaPath($destinationFile)
            );
        }

        return str_replace('\\', '/', $destinationFile);
    }

    /**
     * @param string $file
     * @return string
     * @since 101.0.0
     */
    protected function getFilenameFromTmp($file)
    {
        return strrpos($file, '.tmp') == strlen($file) - 4 ? substr($file, 0, strlen($file) - 4) : $file;
    }

    /**
     * Returns safe filename for posted image
     *
     * @param string $file
     * @return string
     */
    private function getSafeFilename($file)
    {
        $file = DIRECTORY_SEPARATOR . ltrim($file, DIRECTORY_SEPARATOR);

        return $this->mediaDirectory->getDriver()->getRealPathSafety($file);
    }

    /**
     * Check whether file to move exists. Getting unique name
     *
     * @param string $file
     * @param bool $forTmp
     * @return string
     * @since 101.0.0
     */
    protected function getUniqueFileName($file, $forTmp = false)
    {
        if ($this->fileStorageDb->checkDbUsage()) {
            $destFile = $this->fileStorageDb->getUniqueFilename(
                $this->getBaseMediaPathAddition(),
                $file
            );
        } else {
            $destinationFile = $forTmp
                ? $this->mediaDirectory->getAbsolutePath($this->getTmpMediaPath($file))
                : $this->mediaDirectory->getAbsolutePath($this->getMediaPath($file));
            $destFile = dirname($file) . '/' . FileUploader::getNewFileName($destinationFile);
        }

        return $destFile;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getTmpMediaPath($file)
    {
        return $this->getBaseTmpMediaPath() . '/' . $this->_prepareFile($file);
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
     * Part of URL of temporary post images
     * relatively to media folder
     *
     * @param string $file
     * @return string
     */
    public function getTmpMediaShortUrl($file)
    {
        return 'tmp/' . $this->getBaseMediaPathAddition() . '/' . $this->_prepareFile($file);
    }

    /**
     * Part of URL of post images relatively to media folder
     *
     * @param string $file
     * @return string
     */
    public function getMediaShortUrl($file)
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
        return $mediaUrl . $this->getMediaShortUrl($file);
    }
    /**
     * @param $file
     * @return array
     */
    public function prepareImage($file)
    {
        return [
            'name' => 'full-image-4016x6016.jpg',
          'type' => 'image/jpeg',
          'error' => '0',
          'size' => '2053859',
          'file' => '/f/u/full-image-4016x6016.jpg.tmp',
          'url' => 'http://local.niuniu.com/pub/media/tmp/blog/post/f/u/full-image-4016x6016.jpg',
          'previewType' => 'image'
//            'previewType' => 'image',
////            'type' => mime_content_type($file),
//            'name' => basename($file),
//            'error' => 0,
//            'size' => 2053859,
//            'file' => $file,
//            'url' => $this->getMediaUrl($file)
        ];
    }
}
