<?php
namespace Ecommage\Blog\Ui\Component\Listing\Column\Post;
/**
 * Class PageActions
 * @package Ecommage\Blog\Ui\Component\Listing\Column\Post
 */
class PageActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            $indexField = $this->getData('config/indexField');
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";
                if (isset($item[$indexField])) {
                    $id = $item[$indexField];
                }
                $item[$name]["view"] = [
                    "href"=>$this->getContext()->getUrl(
                        "blog/post/edit",[$indexField=>$id]),
                    "label"=>__("Edit")
                ];
            }
        }

        return $dataSource;
    }    
    
}
