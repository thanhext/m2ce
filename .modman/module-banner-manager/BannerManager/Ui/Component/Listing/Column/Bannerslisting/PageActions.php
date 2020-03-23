<?php

namespace T2N\BannerManager\Ui\Component\Listing\Column\Bannerslisting;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class PageActions
 *
 * @package T2N\BannerManager\Ui\Component\Listing\Column\Bannerslisting
 */
class PageActions extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id   = "X";
                if (isset($item["entity_id"])) {
                    $id = $item["entity_id"];
                }
                $item[$name]["view"] = [
                    "href"  => $this->getContext()->getUrl(
                        "banners/banner/edit",
                        ["id" => $id]
                    ),
                    "label" => __("Edit")
                ];
            }
        }

        return $dataSource;
    }
}
