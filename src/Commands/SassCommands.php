<?php

namespace Drupal\iq_barrio_helper\Commands;

use Consolidation\AnnotatedCommand\CommandData;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drush\Commands\core\CacheCommands;
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
    $sassCommands = \Drupal::service('iq_scss_compiler.sass_commands');
    $sassCommands->watch($options);
  }

  /**
   * Interpolates configuration values in the scss definition file.
   *
   * @options folders Whether or not an extra message should be displayed to the user.
   *
   * @command iq_barrio_helper:sass-interpolate-config
   * @aliases iq_barrio_helper-sass-interpolate-config
   *
   * @usage drush iq_barrio_helper:sass-interpolate-config
   */
  public function interpolateConfig() {
    $service = \Drupal::service('iq_barrio_helper.iq_barrio_service');
    $service->interpolateConfig();
  }

  /**
   * Compile scss.
   *
   * @options folders Whether or not an extra message should be displayed to the user.
   *
   * @command iq_barrio_helper:sass-compile
   * @aliases iq_barrio_helper-sass-compile
   *
   * @usage drush iq_barrio_helper:sass-compile --folders=themes,modules,sites/default/files/styling_profiles --continueOnErrors=false
   */
  public function compile($options = ['folders' => 'themes,modules,sites/default/files/styling_profiles', 'continueOnErrors' => FALSE]) {
    $sassCommands = \Drupal::service('iq_scss_compiler.sass_commands');
    $sassCommands->compile($options);
  }

  /**
   * Run SASS compilations after deploy.
   *
   * @hook post-command deploy:hook
   */
  public function deploy($result, CommandData $commandData) {
    $this->interpolateConfig();
    $this->compile();
    CacheCommands::clearThemeRegistry();
    CacheCommands::clearRender();
    return;
  }

}
