<?php

namespace Drupal\iq_barrio_helper\Commands;

use Drupal\Core\Form\FormState;
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
        $compilationService->addSource(DRUPAL_ROOT . '/' . $folder);
      }
    }
    echo 'Starting sass watch' . "\n";
    $compilationService->watch($ttl);
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
    $theme_settings = \Drupal::config('system.theme.global')->get() + \Drupal::config('iq_barrio.settings')->get();
    $form_state = new FormState();
    $form_state->setValues($theme_settings);
    \Drupal::service('theme_handler')->getTheme('iq_barrio')->load();
    iq_barrio_form_system_theme_settings_submit([], $form_state);
  }

  /**
   * Compile scss
   *
   * @options folders Whether or not an extra message should be displayed to the user.
   *
   * @command iq_barrio_helper:sass-compile
   * @aliases iq_barrio_helper-sass-compile
   *
   * @usage drush iq_barrio_helper:sass-compile --folders=themes,modules,sites/default/files/styling_profiles
   */
  public function compile($options = ['folders' => 'themes,modules,sites/default/files/styling_profiles']) {
    $folders = explode(',', str_replace('}', '', str_replace('{', '', $options['folders'])));

    $compilationService = \Drupal::service('iq_barrio_helper.compilation_service');
    foreach ($folders as $folder) {
      $folder = trim($folder);
      if (!empty($folder)) {
        $compilationService->addSource(DRUPAL_ROOT . '/' . $folder);
      }
    }
    echo 'Compiling SASS' . "\n";
    $compilationService->compile();
  }
}
