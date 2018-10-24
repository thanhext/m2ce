<?php
namespace Ecommage\Blog\Setup;

use Ecommage\Blog\Helper\Data;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
/**
 * Class InstallSchema
 * @package Ecommage\Blog\Setup
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        //START table setup
        /**
         * Create table 'ecommage_post'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('ecommage_post')
        )->addColumn(
            Data::FIELD_ID,
            Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'Entity ID'
        )->addColumn(
            Data::FIELD_URL_KEY,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Post String Identifier'
        )->addColumn(
            'featured_src',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Featured src'
        )->addColumn(
            'featured_alt',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Featured Alt'
        )->addColumn(
            'short_description',
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => false],
            'Short Description'
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Title'
        )->addColumn(
            'content',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Post Content'
        )->addColumn(
            'meta_title',
            Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Meta Title'
        )->addColumn(
            'meta_keywords',
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'Post Meta Keywords'
        )->addColumn(
            'meta_description',
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'Post Meta Description'
        )->addColumn(
            'creation_time',
            Table::TYPE_TIMESTAMP,
            null,
            [ 'nullable' => false, 'default' => Table::TIMESTAMP_INIT, ],
            'Creation Time'
        )->addColumn(
            'update_time',
            Table::TYPE_TIMESTAMP,
            null,
            [ 'nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE, ],
            'Modification Time'
        )->addColumn(
            Data::FIELD_STATUS,
            Table::TYPE_SMALLINT,
            null,
            [ 'nullable' => false, 'default' => '1', ],
            'Status'
        )->addIndex(
            $installer->getIdxName('ecommage_post', ['identifier']),
            ['identifier']
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('ecommage_post'),
                ['title', 'meta_keywords', 'meta_description', 'identifier', 'content'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title', 'meta_keywords', 'meta_description', 'identifier', 'content'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Ecommage Post Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ecommage_post_store'
         */
//        $table = $installer->getConnection()->newTable(
//            $installer->getTable('ecommage_post_store')
//        )->addColumn(
//            'post_id',
//            Table::TYPE_SMALLINT,
//            null,
//            ['nullable' => false, 'primary' => true],
//            'Post ID'
//        )->addColumn(
//            'store_id',
//            Table::TYPE_SMALLINT,
//            null,
//            ['unsigned' => true, 'nullable' => false, 'primary' => true],
//            'Store ID'
//        )->addIndex(
//            $installer->getIdxName('ecommage_post_store', ['store_id']),
//            ['store_id']
//        )->addForeignKey(
//            $installer->getFkName('ecommage_post_store', 'post_id', 'ecommage_post', 'post_id'),
//            'post_id',
//            $installer->getTable('ecommage_post'),
//            'post_id',
//            Table::ACTION_CASCADE
//        )->addForeignKey(
//            $installer->getFkName('ecommage_post_store', 'store_id', 'store', 'store_id'),
//            'store_id',
//            $installer->getTable('store'),
//            'store_id',
//            Table::ACTION_CASCADE
//        )->setComment(
//            'Ecommage Post To Store Linkage Table'
//        );
//        $installer->getConnection()->createTable($table);
        //END   table setup
        $installer->endSetup();
    }
}
