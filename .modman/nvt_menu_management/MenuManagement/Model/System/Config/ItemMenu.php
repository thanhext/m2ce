<?php
namespace NVT\MenuManagement\Model\System\Config;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class ItemMenu extends AbstractSource implements SourceInterface, OptionSourceInterface
{

    public function getAllOptions()
    {
        $result = [];
        $data = $this->toOptionArray();
        if($data){
            foreach ($data as $index => $item) {
                $result[] = ['value' => $item['value'], 'label' => $item['label']];

            }
        }
        return $result;
    }

    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $objectManager->create('NVT\MenuManagement\Model\ResourceModel\Menu\Collection');

        /** Apply filters here */
        $collection->addFieldToFilter('is_active', ['is_active' => [\NVT\MenuManagement\Model\System\Config\Status::STATUS_ENABLED]]);
        $collection->addFieldToSelect(['value'=>'menu_id','label'=>'title']);
        $data =  $collection->load()->getData();

        return $data;
    }





}