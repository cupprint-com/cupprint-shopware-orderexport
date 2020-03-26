<?php

/**
 * CupPrint Plugins
 * Copyright (c) CupPrint
 */

namespace cpp_sage200\Bootstrap;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Model\ModelManager;
use Shopware_Components_Acl;

class Installer {

    /**
     * @var int
     */
    private $pluginId;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var CrudService
     */
    private $crudService;

    /**
     * @var ModelManager
     */
    private $em;

    /**
     * @var Shopware_Components_Acl
     */
    private $acl;

    /**
     * @param int                     $pluginId
     * @param Connection              $connection
     * @param CrudService             $crudService
     */
    public function __construct(
        $pluginId,
        Connection $connection,
        CrudService $crudService
    ) {
        $this->pluginId = $pluginId;
        $this->connection = $connection;
        $this->crudService = $crudService;
    }

    /**
     * @return bool
     */
    public function install() {
        $this->createTables();
        $this->createAttributes();
        # $this->installCustomFacet();

        return true;
    }

    /**
     * Creates all tables by loading the SQL files from /Assets/Installation/*.sql
     */
    private function createTables() {
        $fileContent = file_get_contents( __DIR__ . '/Assets/create_tables.sql' );

        $this->connection->executeQuery( $fileContent );
    }

    private function createAttributes() {
        $schemaManager = new SchemaManager();
        $attributes = $schemaManager->getAttributes();

        foreach ( $attributes as $attribute ) {
            $insert = array();

            foreach( array('label','supportText','helpText','translatable','displayInBackend','position','entity','custom','arrayStore') AS $valuename ) {
                if( array_key_exists( $valuename, $attribute ) ) {
                    $insert[$valuename] = $attribute[ $valuename ];
                }
            }

            $this->crudService->update(
                $attribute['table'],
                $attribute['column'],
                $attribute['type'],
                $insert,
                null,
                false,
                $attribute['default']

            );
        }
    }

    /**
     * Registers the facet for this plugin with the custom listing feature.
     */
    private function installCustomFacet() {
//         $sql = <<<SQL
// INSERT IGNORE INTO s_search_custom_facet (
//     unique_key, active, display_in_categories, position, name, facet, deletable
// ) VALUES (
//     'CustomProductsFacet', 0, 1, 60, 'CustomProducts Filter', 
//     '{"SwagCustomProducts\\\\\\\Bundle\\\\\\\SearchBundle\\\\\\\Facet\\\\\\\CustomProductsFacet":{"label":"Custom Products"}}', 
//     0
// ) ON DUPLICATE KEY UPDATE `facet` = '{"SwagCustomProducts\\\\\\\Bundle\\\\\\\SearchBundle\\\\\\\Facet\\\\\\\CustomProductsFacet":{"label":"Custom Products"}}';
// SQL;
//         $this->connection->executeUpdate($sql);
    }
}
