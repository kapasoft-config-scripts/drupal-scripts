#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$theme_name = 'cascade';
$ver = '0.6.1';
$package_name = 'designssquare-com-theme-cascade';
$module_dir = '/Users/maxit/Sites/drupal/modules/';
$theme_dir = '/Users/maxit/Sites/drupal/themes/';
$dist_dir = '/Users/maxit/Sites/drupal/dist/';
$debug = true;
$git_mod_repo = 'https://github.com/kapasoft-drupal-modules/theme_cascade.git';
//@ToDo Fix ssh keys with drush to avoid prompt
//$git_mod_repo = 'git://github.com-kapasoft-drupal-modules:kapasoft-drupal-modules/theme_cascade.git';
$git_theme_base_repo = 'git@github.com:kapasoft-drupal-themes/bootstrap.git';
$git_theme_custom_repo = 'git@github.com:kapasoft-drupal-themes/theme_cascade.git';
$dest = $dist_dir.'themes/'.$theme_name.'/'.$package_name.'-'.$ver;

if(file_exists($dest)){
    //clean
    drush_print('Build '.$package_name.' with ver:'.$ver.' exists...deleting');
    drush_shell_exec('sudo rm -R '.$dest);
    print_r(drush_shell_exec_output());
    //removing old zip file as well
    drush_shell_exec('sudo rm '.$dest.'.zip');
    print_r(drush_shell_exec_output());
}

//build theme module
$widget_name = 'cascade_theme';
//$widget_path = $module_dir.$widget_name;
$widget_dist_dest = $dest.'/modules/designssquare_com_cascade_theme_module';
drush_print('building '.$widget_name.' module ...');
//($debug) ? drush_print('From: '. $widget_path) : '';
//($debug) ? drush_print('To: '. $widget_dist_dest) : '';
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_dest);
//drush_op_system('git clone '.$git_mod_repo.' '.$widget_dist_dest);
drush_shell_exec('git clone '.$git_mod_repo.' '.$widget_dist_dest);
print_r(drush_shell_exec_output());


//build base theme
$widget_name = 'bootstrap';
$widget_path = $theme_dir.$widget_name;
$widget_dist_base_dest = $dest.'/theme/'.$widget_name;
drush_print('building '.$widget_name.' theme ...');
drush_shell_exec('git clone '.$git_theme_base_repo.' '.$widget_dist_base_dest);
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_base_dest);
print_r(drush_shell_exec_output());

//build cascade theme
$widget_name = $theme_name;
$widget_path = $theme_dir.$widget_name;
$widget_dist_custom_dest = $dest.'/theme/'.$widget_name;
drush_print('building '.$widget_name.' theme ...');
drush_shell_exec('git clone '.$git_theme_custom_repo.' '.$widget_dist_custom_dest);
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_custom_dest);
print_r(drush_shell_exec_output());


//copy documenation
drush_print('copying documentation ....'.$dest.'/index.html from '.$widget_dist_custom_dest.'/docs/index.html');
drush_shell_exec('sudo sudo cp '.$widget_dist_custom_dest.'/docs/index.html '.$dest.'/index.html');
print_r(drush_shell_exec_output());


//create zip file
drush_print('ziping package....');
drush_shell_exec('zip -r '.$dest.'.zip '.$dest);
print_r(drush_shell_exec_output());


drush_print('Done building '.$package_name.' ver-'.$ver);