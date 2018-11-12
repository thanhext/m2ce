<?php
namespace AstralWeb\Banner\Ui\Component\Listing\Column\Grid;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";
                if(isset($item["banner_id"]))
                {
                    $id = $item["banner_id"];
                }
                $item[$name]["view"] = [
                    "href"=>$this->getContext()->getUrl(
                        "banner_grid/banner/edit",["banner_id"=>$id]),
                    "label"=>__("Edit")
                ];
            }
        }

        return $dataSource;
    }    
    
}
