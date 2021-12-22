<?php

/**
 * @file
 * Deployment hooks.
 *
 * This is a NAME.deploy.php file. It contains "deploy" functions.
 * These are one-time functions that run *after* config is imported
 * during a deployment. These are a higher level alternative to
 * hook_update_n and hook_post_update_NAME functions.
 *
 * @link https://www.drush.org/latest/deploycommand/#authoring-update-functions  for a detailed comparison. @endlink
 */

/**
 * Themes compilation on deployment.
 */
function iq_barrio_helper_deploy_themes() {
  // Interpolate configuration values in the scss definition file.
  $service = \Drupal::service('iq_barrio_helper.iq_barrio_service');
  $service->interpolateConfig();

  // Scss compilation options.
  $options = [
      'folders' => 'themes,modules,sites/default/files/styling_profiles',
      'continueOnErrors' => false
  ];

  // Compile scss in themes.
  $sassCommands = \Drupal::service('iq_scss_compiler.sass_commands');
  $sassCommands->compile($options);

  return 'Deployed themes successfully';
}

/**
 * Resets deploy hooks.
 */
function iq_barrio_helper_deploy_themes_reset_a() {
  $hooks = [
    "iq_barrio_helper_deploy_themes",
    "iq_barrio_helper_deploy_themes_reset_a",
    "iq_barrio_helper_deploy_themes_reset_b",
  ];
  // Reset deploy hooks.
  $key_value = \Drupal::keyValue('deploy_hook');
  $update_list = $key_value->get('existing_updates');
  if ($update_list) {
    foreach ($hooks as $hook) {
      $index = array_search($hook, $update_list);
      if ($index !== false) {
        unset($update_list[$index]);
      }
    }
    $key_value->set('existing_updates', $update_list);
  }
}

/**
 * Resets deploy hooks.
 */
function iq_barrio_helper_deploy_themes_reset_b() {
  // Reset deploy hooks.
  iq_barrio_helper_deploy_themes_reset_a();
}
