<?php

/**
 * @Author: Thomas Nguyen
 * @Date:   2020-04-07 11:15:40
 * @Last Modified by:   vanthanh245
 * @Last Modified time: 2017-07-05 08:33:08
 * @mail: thanhext@gmail.com
 */

namespace T2N\CustomerAvatar\Plugin\Metadata\Form;

class Image
{
    protected $validImage;

    public function __construct(
        \T2N\CustomerAvatar\Model\Source\Validation\Image $validImage
    ) {
        $this->validImage = $validImage;
    }

    /**
     * {@inheritdoc}
     *
     * @return ImageContentInterface|array|string|null
     */
    public function beforeExtractValue(\Magento\Customer\Model\Metadata\Form\Image $subject, $value)
    {
        $attrCode = $subject->getAttribute()->getAttributeCode();

        if ($this->validImage->isImageValid('tmp_name', $attrCode) === false) {
            $_FILES[$attrCode]['tmpp_name'] = $_FILES[$attrCode]['tmp_name'];
            unset($_FILES[$attrCode]['tmp_name']);
        }

        return [];
    }
}
