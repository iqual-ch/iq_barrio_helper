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
   * @options folders Whether or not an extra message should be displayed to the user.
   *
   * @command iq_barrio_helper:sass-watch
   * @aliases iq_barrio_helper-sass-watch
   *
   * @usage drush iq_barrio_helper:sass-watch --folders=themes,modules
   */
  public function watch($options = ['folders' => 'themes', 'ttl' => 60]) {
    $folders = explode(',', str_replace('}', '', str_replace('{', '', $options['folders'])));
    $ttl = $options['ttl'];

    $compilationService = \Drupal::service('iq_barrio_helper.compilation_service');
    foreach ($folders as $folder) {
      $folder = trim($folder);
      if (!empty($folder)) {
        $compilationService->addSource('/var/www/public/' . $folder);
      }
    }

    $compilationService->watch($ttl);
  }

}
