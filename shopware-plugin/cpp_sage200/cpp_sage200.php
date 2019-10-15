<?php
/**
 * CupPrint Plugins
 * Copyright (c) CupPrint
 */

namespace cpp_sage200;

use Shopware\Components\Plugin;

use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;

use cpp_sage200\Bootstrap\Installer;
use cpp_sage200\Bootstrap\Uninstaller;
use cpp_sage200\Bootstrap\Updater;

use Doctrine\ORM\Tools\SchemaTool;

class cpp_sage200 extends Plugin
{
  private $pluginDirectory;
  private $sloganPrinter;
  private $config;

  /**
   * {@inheritdoc}
   */
  public function install( InstallContext $context ) {
    $installer = new Installer(
        $this->getName(),
        $this->container->get('dbal_connection'),
        $this->container->get('shopware_attribute.crud_service')
    );

    $installer->install();
  }

  /**
   * {@inheritdoc}
   */
  public function update( UpdateContext $context ) {
    $installer = new Installer(
        $this->getName(),
        $this->container->get('dbal_connection'),
        $this->container->get('shopware_attribute.crud_service')
    );
    
    $installer->install();
  }

  /**
   * {@inheritdoc}
   */
  public function uninstall( UninstallContext $context ) {
    $uninstaller = new Uninstaller(
        $this->getName(),
        $this->container->get('dbal_connection'),
        $this->container->get('shopware_attribute.crud_service')
    );

    if ($context->keepUserData()) {
      return;
    }

    $uninstaller->uninstall();
  }

  /**
   * {@inheritdoc}
   */
  public function activate( ActivateContext $context ) {
    parent::install( $this->requestClearCache( $context ) );
  }

  /**
   * {@inheritdoc}
   */
  public function deactivate( DeactivateContext $context ) {
    parent::install( $this->requestClearCache( $context ) );
  }

  /**
   * @param InstallContext | UpdateContext | UninstallContext | ActivateContext | DeactivateContext $context
   */
  private function requestClearCache( $context ) {
    $context->scheduleClearCache(
      [
        InstallContext::CACHE_TAG_CONFIG,
        InstallContext::CACHE_TAG_HTTP
      ]);

    return $context;
  }
}