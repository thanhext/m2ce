<?php
namespace Ecommage\Blog\Model\System\Config;
/**
 * Class Status
 * @package Ecommage\Blog\Model\System\Config
 */
class Status implements \Magento\Framework\Option\ArrayInterface
{
    const STATUS_DRAFT_CODE             = 0;
    const STATUS_DRAFT_LABEL            = 'Draft';
    const STATUS_PUBLISHED_CODE         = 1;
    const STATUS_PUBLISHED_LABEL        = 'Published';
    const STATUS_PENDING_REVIEW_CODE    = 2;
    const STATUS_PENDING_REVIEW_LABEL   = 'Pending Review';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::STATUS_DRAFT_CODE,
                'label' => __(self::STATUS_DRAFT_LABEL)
            ],
            [
                'value' => self::STATUS_PUBLISHED_CODE,
                'label' => __(self::STATUS_PUBLISHED_LABEL)
            ],
            [
                'value' => self::STATUS_PENDING_REVIEW_CODE,
                'label' => __(self::STATUS_PENDING_REVIEW_LABEL)
            ]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            self::STATUS_DRAFT_CODE => __(self::STATUS_DRAFT_LABEL),
            self::STATUS_PUBLISHED_CODE => __(self::STATUS_PUBLISHED_LABEL),
            self::STATUS_PENDING_REVIEW_CODE => __(self::STATUS_PENDING_REVIEW_LABEL)
        ];
    }
}
