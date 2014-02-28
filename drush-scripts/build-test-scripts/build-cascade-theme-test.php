#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$theme_name = 'bazar';
$ver = '0.2';
$package_name = 'designssquare-com-bazar-theme';
$module_dir = '/Users/maxit/Sites/drupal/modules/';
$theme_dir = '/Users/maxit/Sites/drupal/themes/';
$dist_dir = '/Users/maxit/Sites/drupal/dist/';

$dest = $dist_dir.'themes/'.$theme_name.'/'.$package_name.'-'.$ver;

//build commerce module
$widget_name = 'commerce';
drush_print('build '.$widget_name.' module ...');
$widget_path = $module_dir.$widget_name;
drush_print('From: '. $widget_path);
drush_print('To: '. $dest.'/modules/designssquare_com_commerce');
drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$dest.'/modules/designssquare_com_commerce');
print_r(drush_shell_exec_output());

//build blog module
$widget_name = 'blog';
drush_print('build '.$widget_name.' module ...');
$widget_path = $module_dir.$widget_name;
drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$dest.'/modules/designssquare_com_blog');
print_r(drush_shell_exec_output());

//build bazar_theme module
$widget_name = 'bazar_theme';
drush_print('build '.$widget_name.' module ...');
$widget_path = $module_dir.$widget_name;
drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$dest.'/modules/bazar_theme');
print_r(drush_shell_exec_output());

//build theme
$widget_name = 'bazar';
drush_print('build '.$widget_name.' theme ...');
$widget_path = $theme_dir.$widget_name;
drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$dest.'/theme/bazar');
print_r(drush_shell_exec_output());

