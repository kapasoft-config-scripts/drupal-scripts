#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

//$theme_name = 'bazar';
$artifact_name = drush_get_option('artifact-name','bazar');
$ver = drush_get_option('ver','0.4');
$package_name = 'designssquare-com-theme-bazar';
$module_dir = '/Users/maxit/Sites/drupal/modules/';
$theme_dir = '/Users/maxit/Sites/drupal/themes/';
//$test_dir = '/Users/maxit/Sites/drupal/test/';
$test_artifact_dir = getcwd();
//$test_theme_dir = $test_dir.'test-'.$artifact_name;
$dist_dir = '/Users/maxit/Sites/drupal/dist/';
$dist_package = $dist_dir.'themes/'.$artifact_name.'/'.$package_name.'-'.$ver;

//build commerce module
//$widget_name = 'commerce';
//drush_print('build '.$widget_name.' module ...');
//$widget_path = $module_dir.$widget_name;
//drush_print('From: '. $widget_path);
//drush_print('To: '. $dest.'/modules/designssquare_com_commerce');
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$dest.'/modules/designssquare_com_commerce');
//print_r(drush_shell_exec_output());

//build blog module
//$widget_name = 'blog';
//drush_print('build '.$widget_name.' module ...');
//$widget_path = $module_dir.$widget_name;
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$dest.'/modules/designssquare_com_blog');
//print_r(drush_shell_exec_output());

//build bazar_theme module
//$widget_name = 'bazar_theme';
//drush_print('build '.$widget_name.' module ...');
//$widget_path = $module_dir.$widget_name;
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$dest.'/modules/bazar_theme');
//print_r(drush_shell_exec_output());

//building modules
drush_print('building modules ...');
drush_shell_exec('sudo cp -R '.$dist_package.'/modules/ '.$test_artifact_dir.'/sites/all/modules/');
print_r(drush_shell_exec_output());

////build theme
drush_print('build themes ...');
drush_shell_exec('sudo cp -R '.$dist_package.'/theme/ '.$test_artifact_dir.'/sites/all/themes/');
print_r(drush_shell_exec_output());

