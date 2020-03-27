<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\ResourceModel;

use Amasty\Blog\Api\Data\TagInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Tag
 */
class Tag extends AbstractDb
{
    const TABLE_NAME = 'amasty_blog_tags';

    public function _construct()
    {
        $this->_init(self::TABLE_NAME, TagInterface::TAG_ID);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getId() && !$object->hasData(TagInterface::URL_KEY)) {
            $name = str_replace('/', ' ', $object->getData(TagInterface::NAME));
            $slug = $this->generateSlug($name);
            $object->setData(TagInterface::URL_KEY, $slug);
        }

        return parent::_beforeSave($object);
    }

    /**
     * @param $title
     * @return string
     */
    private function generateSlug($title)
    {
        $title = urldecode($title);
        $title = strtolower(
            preg_replace(['/[^\\P{Han}a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'], ['', '-', ''], $title)
        );

        return $title;
    }
}
