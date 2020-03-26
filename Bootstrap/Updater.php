<?php

/**
 * CupPrint Plugins
 * Copyright (c) CupPrint
 */


namespace cppDesignOption\Bootstrap;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Model\ModelManager;

class Updater
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var CrudService
     */
    private $attributeService;

    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * @param Connection   $connection
     * @param CrudService  $attributeService
     * @param ModelManager $modelManager
     */
    public function __construct( Connection $connection, CrudService $attributeService, ModelManager $modelManager ) {
        $this->connection       = $connection;
        $this->attributeService = $attributeService;
        $this->modelManager     = $modelManager;
    }

    /**
     * @param string $version
     */
    public function update( $version ) {
        // if( version_compare( $version, '1.0.0', '<' ) ) {
        //     $this->updateToVersion100();
        // }
    }

    /**
     * @param bool $active
     */
    public function setCustomFacetActiveFlag( $active ) {
        // $this->connection->createQueryBuilder()
        //     ->update('s_search_custom_facet')
        //     ->set('active', ':active')
        //     ->where('unique_key LIKE "CustomProductsFacet"')
        //     ->setParameter('active', $active)
        //     ->execute();
    }

    
    // private function updateToVersion100() {
    //     $sql = "DELETE FROM s_core_subscribes
    //             WHERE listener LIKE 'Shopware_Plugins_Frontend_cppDesignOption_Bootstrap%'";
    //     $this->connection->exec($sql);
    // }


    /**
     * Registers the facet for this plugin with the custom listing feature.
     */
    private function installCustomFacet() {
//         $sql = <<<SQL
// INSERT IGNORE INTO s_search_custom_facet (unique_key, active, display_in_categories, position, name, facet, deletable) VALUES
// ('CustomProductsFacet', 0, 1, 60, 'CustomProducts Filter', '{"cppDesignOption\\\\\\\Bundle\\\\\\\SearchBundle\\\\\\\Facet\\\\\\\CustomProductsFacet":{"label":"cpp Custom Products"}}', 0)
// ON DUPLICATE KEY UPDATE `facet` = '{"cppDesignOption\\\\\\\Bundle\\\\\\\SearchBundle\\\\\\\Facet\\\\\\\CustomProductsFacet":{"label":"cpp Custom Products"}}';
// SQL;
//         $this->connection->executeUpdate( $sql );
    }
}
