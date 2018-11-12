<?php
namespace AstralWeb\EzShipping\Setup;

use Magento\Framework\DB\Ddl\Table;
/**
 * Class InstallSchema
 * @package AstralWeb\EzShipping\Setup
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'shipping_ez'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('shipping_ez')
        )->addColumn(
            'ez_id',
            Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'Entity ID'
        )->addColumn(
            'ez_status',
            Table::TYPE_SMALLINT,
            1,
            [ 'nullable' => false, 'default' => '0' ],
            'EZ Status'
        )->addColumn(
            'order_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Order Id'
        )->addColumn(
            'shipment_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Shipment Id'
        )->addColumn(
            'su_id',
            Table::TYPE_TEXT,
            100,
            [],
            'SuID'
        )->addColumn(
            'increment_id',
            Table::TYPE_TEXT,
            50,
            [],
            'Increment Id (Shipment)'
        )->addColumn(
            'shipment_tracking',
            Table::TYPE_TEXT,
            10,
            [],
            'Tracking Number'
        )->addColumn(
            'shipment_status',
            Table::TYPE_TEXT,
            3,
            [],
            'Shipment Status'
        )->addColumn(
            'shipping_method',
            Table::TYPE_TEXT,
            100,
            [],
            'Shipping Method'
        )->addColumn(
            'shipment_type',
            Table::TYPE_SMALLINT,
            1,
            [ 'nullable' => false, 'default' => '3' ],
            'Shipment Type'
        )->addColumn(
            'collection_amount',
            Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, 'default' => '0' ],
            'Collection Amount'
        )->addColumn(
            'receiver_name',
            Table::TYPE_TEXT,
            60,
            [],
            'Receiver Name'
        )->addColumn(
            'receiver_email',
            Table::TYPE_TEXT,
            120,
            [],
            'Receiver Email'
        )->addColumn(
            'receiver_mobile',
            Table::TYPE_TEXT,
            12,
            [],
            'Receiver Mobile'
        )->addColumn(
            'st_code',
            Table::TYPE_TEXT,
            6,
            [],
            'stCode'
        )->addColumn(
            'rt_url',
            Table::TYPE_TEXT,
            100,
            [],
            'rtUrl'
        )->addColumn(
            'web_para',
            Table::TYPE_TEXT,
            100,
            [],
            'webPara'
        );
        $installer->getConnection()->createTable($table);
        /**
         * add custom shiping_option field
         */
        $this->addShippingOption($installer);

        $installer->endSetup();
    }

    /**
     * @param $installer
     */
    protected function addShippingOption($installer)
    {
        $quoteTable = $installer->getTable('quote');
        $orderTable = $installer->getTable('sales_order');
        $columns = [
            'shipping_option' => [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Shipping Option',
            ],
        ];
        $connection = $installer->getConnection();
        foreach ($columns as $name => $definition) {
            $connection->addColumn($quoteTable, $name, $definition);
            $connection->addColumn($orderTable, $name, $definition);
        }
    }
}
