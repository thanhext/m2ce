<?php

/**
 * @Author: Thomas Nguyen
 * @Date:   2020-04-07 08:33:28
 * @Last Modified by:   vanthanh245
 * @Last Modified time: 2017-07-05 08:30:51
 * @mail: thanhext@gmail.com
 */

namespace T2N\CustomerAvatar\Model\Attribute\Backend;

use \T2N\CustomerAvatar\Model\Source\Validation\Image;

class Avatar extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @param \Magento\Framework\DataObject $object
     *
     * @return $this
     */
    public function beforeSave($object)
    {
        $validation = new Image();
        $attrCode = $this->getAttribute()->getAttributeCode();
        if ($attrCode == 'profile_picture') {
            if ($validation->isImageValid('tmpp_name', $attrCode) === false) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('The profile picture is not a valid image.')
                );
            }
        }

        return parent::beforeSave($object);
    }
}
