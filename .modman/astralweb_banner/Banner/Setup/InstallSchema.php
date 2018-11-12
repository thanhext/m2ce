<?php
namespace AstralWeb\Banner\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;

use AstralWeb\Banner\Api\Data\BannerInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'banners'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('banners')
        )->addColumn(
            BannerInterface::BANNER_ID,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        )->addColumn(
            BannerInterface::IDENTIFIER,
            Table::TYPE_TEXT,
            100,
            ['nullable' => true, 'default' => null],
            'Banner String Identifier'
        )->addColumn(
            BannerInterface::TITLE,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Title'
        )->addColumn(
            BannerInterface::TYPE,
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Banner Type'
        )->addColumn(
            BannerInterface::SORT_ORDER,
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Banner Sort Order'
        )->addColumn(
            BannerInterface::SUFFIX_CLASS,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Suffix Class'
        )->addColumn(
            BannerInterface::CREATION_TIME,
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            BannerInterface::UPDATE_TIME,
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Modification Time'
        )->addColumn(
            BannerInterface::IS_ACTIVE,
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Active'
        )->addColumn(
            BannerInterface::ADDITIONALS,
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'Additionals'
        );
        $installer->getConnection()->createTable($table);


        $installer->endSetup();
    }
}
