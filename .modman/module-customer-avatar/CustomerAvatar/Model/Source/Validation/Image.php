<?php

/**
 * @Author: Thomas Nguyen
 * @Date:   2020-04-07 14:09:36
 * @Last Modified by:   vanthanh245
 * @Last Modified time: 2017-07-05 08:32:11
 * @mail: thanhext@gmail.com
 */

namespace T2N\CustomerAvatar\Model\Source\Validation;

class Image
{
    /**
     * Check the image file
     * @param $tmp_name, $attrCode
     * @return bool
     */
    public function isImageValid($tmp_name, $attrCode)
    {
        if ($attrCode == 'profile_picture') {
            if (!empty($_FILES[$attrCode][$tmp_name])) {
                $imageFile = @getimagesize($_FILES[$attrCode][$tmp_name]);
                if ($imageFile === false) {
                    return false;
                } else {
                    $valid_types = ['image/gif', 'image/jpeg', 'image/png'];
                    if (!in_array($imageFile['mime'], $valid_types)) {
                        return false;
                    }
                }
                return true;
            }
        }
        return true;
    }
}
