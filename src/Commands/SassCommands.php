<?php

namespace Drupal\iq_barrio_helper\Commands;

use Consolidation\AnnotatedCommand\CommandData;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drush\Commands\core\CacheCommands;
use Drush\Commands\DrushCommands;
use Drupal\iq_scss_compiler\Commands\SassCommands as IqScssCompilerCommands;
use Drupal\iq_barrio_helper\Service\IqBarrioService;

/**
 * Sass Drush commands.
 */
class SassCommands extends DrushCommands {

  /**
   * Drupal Logger service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  private $loggerChannelFactory;

  /**
   * Iqual sass commands.
   *
   * @var \Drupal\iq_scss_compiler\Commands\SassCommands
   */
  private $sassCommands;

  /**
   * IqBarrio Helper service.
   *
   * @var \Drupal\iq_barrio_helper\Service\IqBarrioService
   */
  private $iqBarrioService;

  /**
   * Constructs a new SassCommands object.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   *   Logger channel factory service.
   * @param \Drupal\iq_scss_compiler\Commands\SassCommands $sassCommands
   *   Iqual sass commands.
   * @param \Drupal\iq_barrio_helper\Service\IqBarrioService $iqBarrioService
   *   IqBarrio Helper service.
   */
  public function __construct(LoggerChannelFactoryInterface $loggerChannelFactory, IqScssCompilerCommands $sassCommands, IqBarrioService $iqBarrioService) {
    $this->loggerChannelFactory = $loggerChannelFactory;
    $this->sassCommands = $sassCommands;
    $this->iqBarrioService = $iqBarrioService;
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
    $this->sassCommands->watch($options);
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
    $this->iqBarrioService->interpolateConfig();
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
  public function compile($options = [
    'folders' => 'themes,modules,sites/default/files/styling_profiles',
    'continueOnErrors' => FALSE,
  ]) {
    $this->sassCommands->compile($options);
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
  }

}
