<?php

namespace Drupal\iq_barrio_helper\Commands;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drush\Commands\DrushCommands;

/**
 * Sass Drush commands.
 */
class SassCommands extends DrushCommands {

  /**
   * Constructs a new SassCommands object.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   *   Logger channel factory service.
   */
  public function __construct(LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->loggerChannelFactory = $loggerChannelFactory;
  }

  /**
   * Import all products and certificates.
   *
   * @param string $folders
   *   A list of folders to watch.
   *
   * @command iq_barrio_helper:sass-watch
   * @aliases iq_barrio_helper-sass-watch
   *
   * @usage drush iq_barrio_helper:sass-watch themes,modules
   */
  public function watch($folders) {
    $folders = explode(',', str_replace('}', '', str_replace('{', '', $folders)));

    $compilationService = \Drupal::service('iq_barrio_helper.compilation_service');
    foreach($folders as $folder) {
      $compilationService->addSource('/var/www/public/' . trim($folder));
    }
    $compilationService->watch();
  }

}
