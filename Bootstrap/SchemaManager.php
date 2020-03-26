<?php

/**
 * CupPrint Plugins
 * Copyright (c) CupPrint
 */

namespace cpp_sage200\Bootstrap;

/**
 * This class will be used to manage all custom schema adjustments which will be executed in the install and uninstall
 * method of this plugin. This class is used only to manage all classes and attributes at a central place.
 *
 * Class SchemaManager
 */
class SchemaManager {
    /**
     * attributes for the Custom Products plugin
     *
     * @return array
     */
    public function getAttributes() {
        return [
            [
                'table' => 's_articles_attributes',
                'column' => 'cp_sage_stock_code',
                'type' => 'string',
                'default' => '',
                'label' => 'Sage200',
                'supportText' => 'Sage200 Stock-Code for internal use',
                'helpText' => '',
                'position' => 910,
                'displayInBackend' => true,
                'translatable' => false
            ],
            [
                'table' => 's_order_details_attributes',
                'column' => 'cp_sage_purchaseunit',
                'type' => 'string',
                'default' => '',
                'label' => '',
                'supportText' => '',
                'helpText' => '',
                'position' => 910,
                'displayInBackend' => false,
                'translatable' => false
            ],
            [
                'table' => 's_order_details_attributes',
                'column' => 'cp_sage_stock_code',
                'type' => 'string',
                'default' => '',
                'label' => '',
                'supportText' => '',
                'helpText' => '',
                'position' => 910,
                'displayInBackend' => false,
                'translatable' => false
            ],
            [
                'table' => 's_order_basket_attributes',
                'column' => 'cp_sage_purchaseunit',
                'type' => 'string',
                'default' => '',
                'label' => '',
                'supportText' => '',
                'helpText' => '',
                'position' => 910,
                'displayInBackend' => false,
                'translatable' => false
            ],
            [
                'table' => 's_order_basket_attributes',
                'column' => 'cp_sage_stock_code',
                'type' => 'string',
                'default' => '',
                'label' => '',
                'supportText' => '',
                'helpText' => '',
                'position' => 910,
                'displayInBackend' => false,
                'translatable' => false
            ]
        ];
    }
}
