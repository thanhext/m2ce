<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ElasticSearch
 */


namespace Amasty\ElasticSearch\Model\Client;

use Amasty\ElasticSearch\Api\Data\Indexer\Data\DataMapperResolverInterface;
use Amasty\ElasticSearch\Model\Indexer\ExternalIndexerProvider;
use Amasty\ElasticSearch\Model\Indexer\Structure\EntityBuilder\Product;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use \Magento\Framework\Exception\LocalizedException;

/**
 * Class Elasticsearch
 *
 * Client connection manager
 */
class Elasticsearch
{
    const BULK_ACTION_INDEX = 'index';
    const BULK_ACTION_DELETE = 'delete';
    const EXTERNAL_INDEX = 'external';
    const EXTERNAL_INDEX_BATCH_COUNT = 100;

    /**
     * @var \Amasty\ElasticSearch\Model\Config
     */
    private $config;

    /**
     * @var \Elasticsearch\Client
     */
    private $client;

    /**
     * @var \Amasty\ElasticSearch\Api\Data\Indexer\Structure\IndexBuilderInterface
     */
    private $indexBuilder;

    /**
     * @var \Amasty\ElasticSearch\Model\Indexer\Structure\EntityBuilder
     */
    private $entityStructureBuilder;

    /**
     * @var \Amasty\ElasticSearch\Model\Debug
     */
    private $debug;

    /**
     * @var DataMapperResolverInterface
     */
    private $dataMapperResolver;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $indexNameByStore = [];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ExternalIndexerProvider
     */
    private $externalIndexerProvider;

    /**
     * @var \Amasty\ElasticSearch\Model\Indexer\Structure\DynamicTemplateMapper
     */
    private $dynamicTemplateMapper;

    /**
     * @var bool
     */
    private $pingResult = null;

    public function __construct(
        \Amasty\ElasticSearch\Model\Config $config,
        \Amasty\ElasticSearch\Api\Data\Indexer\Structure\IndexBuilderInterface $indexBuilder,
        \Amasty\ElasticSearch\Model\Indexer\Structure\EntityBuilder $entityStructureBuilder,
        \Amasty\ElasticSearch\Api\Data\Indexer\Data\DataMapperResolverInterface $dataMapperResolver,
        \Amasty\ElasticSearch\Model\Debug $debug,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ExternalIndexerProvider $externalIndexerProvider,
        \Amasty\ElasticSearch\Model\Indexer\Structure\DynamicTemplateMapper $dynamicTemplateMapper,
        array $options = []
    ) {
        if (!class_exists(\Elasticsearch\ClientBuilder::class)) {
            throw new LocalizedException(
                __('Elasticsearch package is not installed. Please, run the following command '
                . 'in the SSH: composer require elasticsearch/elasticsearch ~5.1')
            );
        }

        $this->config = $config;
        $this->indexBuilder = $indexBuilder;
        $this->entityStructureBuilder = $entityStructureBuilder;
        $this->debug = $debug;
        $this->dataMapperResolver = $dataMapperResolver;
        $this->storeManager = $storeManager;
        $this->externalIndexerProvider = $externalIndexerProvider;
        $this->dynamicTemplateMapper = $dynamicTemplateMapper;

        try {
            $options = $this->config->prepareConnectionData($options);
            $config = $this->buildConfig($options);
            $client = \Elasticsearch\ClientBuilder::fromConfig($config, true);
        } catch (\Exception $e) {
            $this->debug->debug($e);
            throw new LocalizedException($this->getDefaultErrorMessage());
        }

        $this->setClient($client, getmypid())->setClientOptions($options);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getDefaultErrorMessage()
    {
        return __(
            'It is impossible to perform the search. Please make sure Elasticsearch Engine'
            . ' is installed on the server and configured properly.'
        );
    }

    /**
     * @param array $documentData
     * @param int $storeId
     * @param string $indexerId
     * @return array
     */
    public function prepareDocuments(array $documentData, $storeId, $indexerId)
    {
        $documents = [];
        if (!empty($documentData)) {
            $documents = $this->dataMapperResolver->mapEntityData(
                $documentData,
                $storeId,
                ['indexer_id' => $indexerId]
            );
        }

        return $documents;
    }

    /**
     * @param array $documents
     * @param int $storeId
     * @param string $indexerId
     * @return $this
     * @throws \Exception
     */
    public function saveDocuments(array $documents, $storeId, $indexerId)
    {
        if (!empty($documents)) {
            try {
                $indexName = $this->getIndexName($indexerId, $storeId);
                if ($this->indexExists($indexName)) {
                    $query = $this->prepareSaveQuery($documents, $indexName);
                    $this->bulk($query);
                }
            } catch (\Exception $e) {
                $this->debug->debug($e);
                throw $e;
            }
        }

        return $this;
    }

    /**
     * @throws LocalizedException
     * @return $this
     */
    public function saveExternal()
    {
        foreach ($this->storeManager->getStores() as $store) {
            $batch = 0;
            foreach ($this->externalIndexerProvider->getIndexTypes() as $indexType) {
                $indexName = $this->getIndexName(self::EXTERNAL_INDEX . '_' . $indexType, $store->getId());
                if ($this->indexExists($indexName)) {
                    $this->deleteIndex($indexName);
                }
                while (true) {
                    $documents = $this->externalIndexerProvider->getDocuments(
                        $store->getId(),
                        ++$batch,
                        self::EXTERNAL_INDEX_BATCH_COUNT
                    );
                    if (empty($documents[$indexType])) {
                        break;
                    }
                    $query = $this->prepareSaveQuery($documents[$indexType], $indexName);
                    $this->bulk($query);
                }
            }
        }

        return $this;
    }

    /**
     * @param array $query
     * @return array
     * @throws \Exception
     */
    public function bulk($query)
    {
        $result = $this->getClient()->bulk($query);
        if (!empty($result['errors'])) {
            $message = __('Elasticsearch engine returned an error response. ');
            if (!empty($result['items'])) {
                foreach ($result['items'] as $item) {
                    $item = $item['index'];
                    if (!empty($item['error'])) {
                        $causedBy = '';
                        if (!empty($item['error']['caused_by'])) {
                            $causedBy = ', caused by: "' . $item['error']['caused_by']['type']
                                . '. ' . $item['error']['caused_by']['reason'] . '"';
                        }

                        $message .= __(
                            'item id: %1. Error type: "%2", reason "%3"%4.' . PHP_EOL,
                            $item['_id'],
                            $item['error']['type'],
                            $item['error']['reason'],
                            $causedBy
                        );
                    }
                }
            }
            //@codingStandardsIgnoreLine
            throw new \Exception($message);
        }

        return $result;
    }

    /**
     *
     * @param int $storeId
     * @param string $indexerId
     * @throws LocalizedException
     * @return $this
     */
    public function cleanIndex($storeId, $indexerId)
    {
        $this->checkIndex($storeId, $indexerId);
        $indexName = $this->getIndexName($indexerId, $storeId);
        if ($this->isEmptyIndex($indexName)) {
            return $this;
        }

        $indexPattern = $this->getIndexAlias($indexerId, $storeId) . '_v';
        $version = (int)(str_replace($indexPattern, '', $indexName));
        $newIndexName = $indexPattern . (++$version);

        if ($this->indexExists($newIndexName)) {
            $this->deleteIndex($newIndexName);
        }

        $this->prepareIndex($storeId, $newIndexName, $indexerId);

        return $this;
    }

    /**
     * @param array $documentIds
     * @param int $storeId
     * @param string $indexerId
     * @return $this
     * @throws \Exception
     */
    public function deleteDocuments(array $documentIds, $storeId, $indexerId)
    {
        try {
            $indexName = $this->getIndexName($indexerId, $storeId);
            if (!$this->indexExists($indexName)) {
                return $this;
            }
            $query = $this->prepareSaveQuery(
                $documentIds,
                $indexName,
                self::BULK_ACTION_DELETE
            );
            $this->bulk($query);
        } catch (\Exception $e) {
            $this->debug->debug($e);
            throw $e;
        }

        return $this;
    }

    /**
     * @param array $documents
     * @param string $indexName
     * @param string $action
     * @return array
     */
    protected function prepareSaveQuery(
        $documents,
        $indexName,
        $action = self::BULK_ACTION_INDEX
    ) {
        $bulkArray = [
            'index' => $indexName,
            'type' => $this->config->getEntityType(),
            'body' => [],
            'refresh' => true,
        ];

        foreach ($documents as $id => $document) {
            $bulkArray['body'][] = [
                $action => [
                    '_id' => $id,
                    '_type' => $this->config->getEntityType(),
                    '_index' => $indexName
                ]
            ];
            if ($action == self::BULK_ACTION_INDEX) {
                $bulkArray['body'][] = $document;
            }
        }

        return $bulkArray;
    }

    /**
     * @param int $storeId
     * @param string $indexerId
     * @throws NoNodesAvailableException
     * @throws LocalizedException
     * @return $this
     */
    public function checkIndex($storeId, $indexerId)
    {
        $indexName = $this->getIndexName($indexerId, $storeId);
        if (!$this->indexExists($indexName)) {
            $this->prepareIndex($storeId, $indexName, $indexerId);
        }

        $alias = $this->getIndexAlias($indexerId, $storeId);
        if (!$this->existsAlias($alias, $indexName)) {
            $params['body']['actions'][] = ['add' => ['alias' => $alias, 'index' => $indexName]];
            $this->getClient()->indices()->updateAliases($params);
        }
        return $this;
    }

    /**
     * @param $storeId
     * @param $indexName
     * @param $indexerId
     * @throws LocalizedException
     * @return $this
     */
    private function prepareIndex($storeId, $indexName, $indexerId)
    {
        $this->indexBuilder->setStoreId($storeId);
        $this->createIndex($indexName, ['settings' => $this->indexBuilder->build()]);
        $this->prepareFieldMapping(
            $this->entityStructureBuilder->build($indexerId),
            $indexName,
            $storeId
        );
        $this->indexNameByStore[$storeId] = $indexName;
        return $this;
    }

    /**
     * Execute suggest query
     *
     * @param array $query
     * @throws LocalizedException
     * @return array
     */
    public function suggest($query)
    {
        return $this->getClient()->suggest($query);
    }

    /**
     * @throws LocalizedException
     * @return bool
     */
    public function ping()
    {
        if ($this->pingResult == null) {
            $this->pingResult = $this->getClient()->ping(['client' => ['timeout' => $this->options['timeout']]]);
        }

        return (bool)$this->pingResult;
    }

    /**
     * @param $query
     * @return array
     * @throws Missing404Exception
     * @throws LocalizedException
     */
    public function search($query)
    {
        try {
            return $this->getClient()->search($query);
            //@codingStandardsIgnoreLine
        } catch (Missing404Exception $e) {
            $message = __('Elasticsearch index not found. Run "bin/magento indexer:reindex catalogsearch_fulltext".');
            //@codingStandardsIgnoreLine
            throw new Missing404Exception($message, 0 , $e);
        }
    }

    /**
     * @param array $options
     * @throws LocalizedException
     * @return array
     */
    private function buildConfig($options = [])
    {
        if (empty($options['hostname'])
            || (array_key_exists('enableAuth', $options) && 1 == $options['enableAuth']
                && (empty($options['username']) || empty($options['password'])))
        ) {
            throw new LocalizedException($this->getDefaultErrorMessage());
        }

        $host = preg_replace('/http[s]?:\/\//i', '', $options['hostname']);
        //@codingStandardsIgnoreLine
        $protocol = parse_url($options['hostname'], PHP_URL_SCHEME) ?: 'http';

        if (!empty($options['port'])) {
            $host .= ':' . $options['port'];
        }

        if (!empty($options['enableAuth']) && ($options['enableAuth'] == 1)) {
            $host = sprintf('%s:%s@%s', $options['username'], $options['password'], $host);
        }

        $host = sprintf('%s://%s', $protocol, $host);
        $options['hosts'] = [$host];
        return $options;
    }

    /**
     * @param string $index
     * @param array $settings
     * @throws LocalizedException
     * @return $this
     */
    public function createIndex($index, $settings)
    {
        $this->getClient()->indices()->create([
            'index' => $index,
            'body' => $settings,
        ]);

        return $this;
    }

    /**
     * @param string $index
     * @throws LocalizedException
     * @return bool
     */
    public function isEmptyIndex($index)
    {
        $result = true;
        $stats = $this->getClient()->indices()->stats(['index' => $index, 'metric' => 'docs']);
        if (isset($stats['indices'][$index]) && $stats['indices'][$index]['primaries']['docs']['count'] > 0) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param string $index
     * @throws LocalizedException
     * @return void
     */
    public function deleteIndex($index)
    {
        $this->getClient()->indices()->delete(['index' => $index]);
    }

    /**
     * @param $indexerId
     * @param $storeId
     * @throws LocalizedException
     * @return $this
     */
    public function updateAlias($indexerId, $storeId)
    {
        if (!isset($this->indexNameByStore[$storeId])) {
            return $this;
        }

        $alias = $this->getIndexAlias($indexerId, $storeId);
        $oldIndex = $this->getIndexNameByAlias($alias, $storeId);

        if (!empty($oldIndex)) {
            $params['body']['actions'][] = ['remove' => ['alias' => $alias, 'index' => $oldIndex]];
        }
        $params['body']['actions'][] = ['add' => ['alias' => $alias, 'index' => $this->indexNameByStore[$storeId]]];

        $this->getClient()->indices()->updateAliases($params);

        if ($oldIndex) {
            $this->deleteIndex($oldIndex);
        }

        return $this;
    }

    /**
     * @param string $alias
     * @param string $newIndex
     * @param string $oldIndex
     * @throws LocalizedException
     * @return $this
     */
    public function updateClientAlias($alias, $newIndex, $oldIndex = '')
    {
        $params['body'] = ['actions' => []];
        if ($oldIndex) {
            $params['body']['actions'][] = ['remove' => ['alias' => $alias, 'index' => $oldIndex]];
        }

        if ($newIndex) {
            $params['body']['actions'][] = ['add' => ['alias' => $alias, 'index' => $newIndex]];
        }

        $this->getClient()->indices()->updateAliases($params);

        return $this;
    }

    /**
     * @param $index
     * @return mixed
     * @throws LocalizedException
     */
    public function indexExists($index)
    {
        return $this->getClient()->indices()->exists(['index' => $index]);
    }

    /**
     * @param $alias
     * @param string $index
     * @return mixed
     * @throws LocalizedException
     * @throws NoNodesAvailableException
     */
    public function existsAlias($alias, $index = '')
    {
        $params = ['name' => $alias];
        if ($index) {
            $params['index'] = $index;
        }

        try {
            return $this->getClient()->indices()->existsAlias($params);
            //@codingStandardsIgnoreLine
        } catch (NoNodesAvailableException $e) {
            $message = __('Elasticsearch server not found. Check "Stores > Configuration'
                . ' > Amasty Elastic Search > Connection" and make sure that "Test Connection"'
                .' is successfull with appropriate host name and port.');
            //@codingStandardsIgnoreLine
            throw new NoNodesAvailableException($message, 0 , $e);
        }
    }

    /**
     * @param $alias
     * @return mixed
     * @throws LocalizedException
     */
    public function getAlias($alias)
    {
        return $this->getClient()->indices()->getAlias(['name' => $alias]);
    }

    /**
     * @param array $fields
     * @param string $index
     * @param int $storeId
     * @throws LocalizedException
     * @return void
     */
    public function prepareFieldMapping($fields, $index, $storeId = null)
    {
        $entityType = $this->config->getEntityType();
        $params = [
            'index' => $index,
            'type' => $entityType,
            'body' => [
                $entityType => [
                    'properties' => [],
                    'dynamic_templates' => [
                        $this->dynamicTemplateMapper->map($storeId)
                    ]
                ],
            ],
        ];

        foreach ($fields as $field => $fieldInfo) {
            if ($fieldInfo['type'] === Product::ATTRIBUTE_TYPE_TEXT) {
                $fieldInfo['fielddata'] = true;

                if ($this->config->allowSpecialChars($storeId)) {
                    $fieldInfo['analyzer'] = 'allow_spec_chars';
                }

                if ($this->config->hasStemming($storeId)) {
                    $fieldInfo['fields']['stemming'] = [
                        'type' => Product::ATTRIBUTE_TYPE_TEXT,
                        'fielddata' => true,
                        'analyzer' => 'stem',
                        'index' => true
                    ];
                }
            }

            $params['body'][$entityType]['properties'][$field] = $fieldInfo;
        }

        $info = $this->getClient()->info();

        if (isset($info['version']['number'])
            && version_compare($info['version']['number'], '7.0.0', '>=')
        ) {
            $params['include_type_name'] = true;
        }

        $this->getClient()->indices()->putMapping($params);
    }

    /**
     * @param $index
     * @param $entityType
     * @throws LocalizedException
     */
    public function deleteMapping($index, $entityType)
    {
        $this->getClient()->indices()->deleteMapping([
            'index' => $index,
            'type' => $entityType,
        ]);
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    public function getClient()
    {
        $pid = getmypid();

        if (!isset($this->client[$pid])) {
            $config = $this->buildConfig($this->getClientOptions());
            $this->client[$pid] = \Elasticsearch\ClientBuilder::fromConfig($config, true);
        }

        return $this->client[$pid];
    }

    /**
     * @param \Elasticsearch\Client $client
     * @return $this
     */
    public function setClient($client, $pid)
    {
        $this->client[$pid] = $client;

        return $this;
    }

    /**
     * @return array
     */
    public function getClientOptions()
    {
        return $this->options;
    }

    /**
     * @param array $clientOptions
     * @return $this
     */
    public function setClientOptions($clientOptions)
    {
        $this->options = $clientOptions;

        return $this;
    }

    /**
     * @param string $indexerId
     * @param int $storeId
     * @return string
     */
    public function getIndexName($indexerId, $storeId)
    {
        if (strpos($indexerId, self::EXTERNAL_INDEX) === 0) {
            return $this->getIndexAlias($indexerId, $storeId);
        }

        if (!isset($this->indexNameByStore[$storeId])) {
            $alias = $this->getIndexAlias($indexerId, $storeId);
            $indexName = $this->getIndexNameByAlias($alias, $storeId);

            if (empty($indexName)) {
                $indexName = $alias . '_v1';
            }

            return $indexName;
        }
        return $this->indexNameByStore[$storeId];
    }

    /**
     * @param string $indexerId
     * @param int $storeId
     * @return string
     */
    public function getIndexAlias($indexerId, $storeId)
    {
        return $this->config->getIndexName($indexerId, $storeId);
    }

    /**
     * @param string $alias
     * @param int $storeId
     * @throws LocalizedException
     * @throws NoNodesAvailableException
     * @return string
     */
    public function getIndexNameByAlias($alias, $storeId)
    {
        $storeIndex = '';
        $indexPattern = $alias . '_v';
        if ($this->existsAlias($alias)) {
            $alias = $this->getAlias($alias);
            $indices = array_keys($alias);
            foreach ($indices as $index) {
                if (strpos($index, $indexPattern) === 0) {
                    if (isset($this->indexNameByStore[$storeId]) && $this->indexNameByStore[$storeId] == $index) {
                        continue;
                    }
                    $storeIndex = $index;
                    break;
                }
            }
        }

        return $storeIndex;
    }
}
