<?php

namespace T2N\BannerManager\Ui\Component\Listing\Column\Banner;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class PageActions
 *
 * @package T2N\BannerManager\Ui\Component\Listing\Column\Banner
 */
class PageActions extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            $name       = $this->getData("name");
            $urlEdit    = $this->getConfig("urlEdit", "*/*/edit");
            $indexField = $this->getConfig("indexField");
            foreach ($dataSource["data"]["items"] as & $item) {
                $id                  = $item[$indexField] ?? null;
                $item[$name]["view"] = [
                    "href"  => $this->getContext()->getUrl($urlEdit, ["id" => $id]),
                    "label" => __("Edit")
                ];
            }
        }

        return $dataSource;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    protected function getConfig($key, $default = null)
    {
        $config = $this->getConfiguration();
        return $config[$key] ?? $default;
    }
}
