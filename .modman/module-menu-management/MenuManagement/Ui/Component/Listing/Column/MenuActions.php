<?php
namespace NVT\MenuManagement\Ui\Component\Listing\Column;

class MenuActions extends \Magento\Ui\Component\Listing\Columns\Column {

    const URL_PATH_EDIT = 'menumanager/menu/edit';
    const URL_PATH_DELETE = 'menumanager/menu/delete';
    protected $urlBuilder;
    private $editUrl;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::URL_PATH_EDIT
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        $identities = \NVT\MenuManagement\Model\Menu::CACHE_TAG . '_id';
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name   = $this->getData('name');
                $id     = (isset($item[$identities])) ? $item[$identities] : $item[$item['id_field_name']];
                $item[$name]['edit'] = [
                    'href' => $this->urlBuilder->getUrl($this->editUrl, [$identities => $id]),
                    'label' => __('Edit')
                ];
                //var_dump('Delete "${ $.$data.file_name }"'); die();
                $item[$name]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, [$identities => $id]),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete "${ $.$data.title }"'),
                        'message' => __('Are you sure you want to delete a "${ $.$data.title }" record?')
                    ]
                ];
            }
        }
        return $dataSource;
    }
}