#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

//$test_dir_name = drush_get_option('test-dir-name');
$name = drush_get_option('artifact-name','blog');
$type = drush_get_option('artifact_type','module');
$artifact_name = 'designssquare_com_'.$name;
$ver = drush_get_option('ver','0.1');
$package_name = 'designssquare-com-'.$type.'-'.$name;
$module_dir = '/Users/maxit/Sites/drupal/modules/';
$test_artifact_dir = getcwd();
$dist_dir = '/Users/maxit/Sites/drupal/dist/';
$dist_package = $dist_dir.'modules/'.$artifact_name.'/'.$package_name.'-'.$ver;

//building modules
drush_print('building modules ...sudo cp -Rv '.$dist_package.'/modules/ '.$test_artifact_dir.'/sites/all/modules/');
drush_shell_exec('sudo cp -R '.$dist_package.'/modules/ '.$test_artifact_dir.'/sites/all/modules/');
print_r(drush_shell_exec_output());

////build theme
//drush_print('build themes ...');
//drush_shell_exec('sudo cp -R '.$dist_package.'/theme/ '.$test_artifact_dir.'/sites/all/themes/');
//print_r(drush_shell_exec_output());

