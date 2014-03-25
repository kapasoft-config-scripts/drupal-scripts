#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$theme_name = 'calypso';
$dest_dir = 'sites/all';
$dest_mod_dir = $dest_dir.'/modules';
$dest_theme_dir = $dest_dir.'/themes';
$debug = true;
//@ToDo Fix ssh keys with drush to avoid prompt
$git_mod_repo = 'https://github.com/kapasoft-drupal-modules/calypso.git';
$git_util_repo = 'https://github.com/kapasoft-config-scripts/designssquare-utils.git';
//https://github.com/kapasoft-drupal-themes/bootstrap.git
$git_theme_base_repo = 'git@github.com:kapasoft-drupal-themes/bootstrap.git';
$git_theme_custom_repo = 'https://github.com/kapasoft-drupal-themes/calypso.git';

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

//build theme module
$widget_name = 'calypso_theme';
$widget_dist_dest = $dest_mod_dir.'/'.$widget_name;
drush_print('building '.$widget_name.' module ...kapasoft-drupal-modules');
drush_shell_exec('git clone '.$git_mod_repo.' '.$widget_dist_dest);
print_r(drush_shell_exec_output());

//build designssqure common module
$widget_name = 'designssquare_lib';
$widget_dist_dest = $dest_mod_dir.'/'.$widget_name;
drush_print('building '.$widget_name.' module ...kapasoft-config-scripts');
drush_shell_exec('git clone '.$git_util_repo.' '.$widget_dist_dest);
print_r(drush_shell_exec_output());

//build base theme
$widget_name = 'bootstrap';
$widget_dist_custom_dest = $dest_theme_dir.'/'.$widget_name;;
drush_print('building '.$widget_name.' theme ...kapasoft-drupal-themes');
drush_shell_exec('git clone '.$git_theme_base_repo.' '.$widget_dist_custom_dest);
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_custom_dest);
print_r(drush_shell_exec_output());

//build custom theme
$widget_name = $theme_name;
$widget_dist_custom_dest = $dest_theme_dir.'/'.$widget_name;;
drush_print('building '.$widget_name.' theme ...kapasoft-drupal-themes');
drush_shell_exec('git clone '.$git_theme_custom_repo.' '.$widget_dist_custom_dest);
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_custom_dest);
print_r(drush_shell_exec_output());

//copy documentation
drush_print('copying documentation from '.$widget_dist_custom_dest.'/docs ...');
if(!file_exists('docs')){
    drush_print('docs folder missing ...creating one');
    drush_shell_exec('mkdir docs');
    print_r(drush_shell_exec_output());
 }
drush_shell_exec('sudo cp -r '.$widget_dist_custom_dest.'/docs/ docs/');
print_r(drush_shell_exec_output());

