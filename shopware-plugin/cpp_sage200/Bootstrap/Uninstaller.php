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

class Uninstaller {
    
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
     * @var SchemaManager
     */
    # private $schemaManager;


    /**
     * @param CrudService             $crudService
     * @param Shopware_Components_Acl $acl
     * @param Connection              $connection
     * @param ModelManager            $em
     */
    public function __construct(
        $pluginId,
        Connection $connection,
        CrudService $crudService
    ) {
        $this->pluginId = $pluginId;
        $this->connection = $connection;
        $this->crudService = $crudService;
        # $this->schemaManager = new SchemaManager();
    }

    /**
     * Deletes all custom database tables which are related to the plugin
     */
    public function uninstall() {
        $this->deleteTableAttributes();
        $this->removeTables();
        # $this->uninstallCustomFacet();
    }

    /**
     * deletes all custom basket attribute columns which are related to the plugin
     */
    public function secureUninstall() {
    }

    /**
     * deletes all custom attributes
     */
    private function deleteTableAttributes() {
        $attributes = $this->schemaManager->getCppDesignOptionAttributes();
        foreach( $attributes as $attribute ) {
            if( $this->crudService->get( $attribute['table'], $attribute['column'] ) ) {
                $this->crudService->delete( $attribute['table'], $attribute['column'] );
            }
        }

        // $this->em->generateAttributeModels([
        //     's_order_basket_attributes',
        //     's_media_attributes',
        //     's_order_details_attributes',
        // ]);
    }

    /**
     * Remove all tables by loading the SQL files from /Assets/Installation/*.sql
     */
    private function removeTables() {
        $fileContent = file_get_contents( __DIR__ . '/Assets/drop_tables.sql' );

        $this->connection->executeQuery( $fileContent );

        $schemaManager = new SchemaManager();
        $attributes = $schemaManager->getCppDesignOptionAttributes();
    }

    /**
     * Removes the entry for custom search facets.
     */
    private function uninstallCustomFacet()
    {
        // $exists = $this->connection->getSchemaManager()->tablesExist(['s_search_custom_facet']);
        // if( !$exists ) {
        //     return;
        // }

        // $this->connection->executeUpdate( "DELETE FROM s_search_custom_facet WHERE unique_key = 'cppDesignOption'" );
    }
}
