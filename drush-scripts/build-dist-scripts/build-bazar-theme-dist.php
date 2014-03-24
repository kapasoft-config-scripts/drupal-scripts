#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$theme_name = 'bazar';
$ver = drush_get_option('ver','0.4');
$package_name = 'designssquare-com-theme-bazar';
$module_dir = '/Users/maxit/Sites/drupal/modules/';
$theme_dir = '/Users/maxit/Sites/drupal/themes/';
$dist_dir = '/Users/maxit/Sites/drupal/dist/';
$debug = true;
//@ToDo Fix ssh keys with drush to avoid prompt
$git_mod_repo = 'https://github.com/kapasoft-drupal-modules/bazar_theme.git';
$git_util_repo = 'https://github.com/kapasoft-config-scripts/designssquare-utils.git';
//$git_mod_repo = 'git://github.com-kapasoft-drupal-modules:kapasoft-drupal-modules/theme_cascade.git';
//$git_theme_base_repo = 'git@github.com:kapasoft-drupal-themes/bootstrap.git';
$git_theme_custom_repo = 'https://github.com/kapasoft-drupal-themes/bazar.git';
$full_package_name = $package_name.'-'.$ver;
$dest_dir = $dist_dir.'themes/'.$theme_name;
$dest = $dest_dir.'/'.$full_package_name;
if(file_exists($dest)){
    //clean
    drush_print('Build: '.$package_name.' with ver:'.$ver.' exists...deleting');
    drush_shell_exec('sudo rm -R '.$dest);
    print_r(drush_shell_exec_output());
    //removing old zip file as well
    drush_shell_exec('sudo rm '.$dest.'.tar.gz');
    print_r(drush_shell_exec_output());
}

//build theme module
$widget_name = 'bazar_theme';
$widget_dist_dest = $dest.'/modules/bazar_theme';
drush_print('building '.$widget_name.' module ...kapasoft-drupal-modules');
//($debug) ? drush_print('From: '. $widget_path) : '';
//($debug) ? drush_print('To: '. $widget_dist_dest) : '';
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_dest);
//drush_op_system('git clone '.$git_mod_repo.' '.$widget_dist_dest);
drush_shell_exec('git clone '.$git_mod_repo.' '.$widget_dist_dest);
print_r(drush_shell_exec_output());

//build designssqure common module
$widget_name = 'designssquare_lib';
$widget_dist_dest = $dest.'/modules/designssquare_lib';
drush_print('building '.$widget_name.' module ...kapasoft-config-scripts');
//($debug) ? drush_print('From: '. $widget_path) : '';
//($debug) ? drush_print('To: '. $widget_dist_dest) : '';
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_dest);
//drush_op_system('git clone '.$git_mod_repo.' '.$widget_dist_dest);
drush_shell_exec('git clone '.$git_util_repo.' '.$widget_dist_dest);
print_r(drush_shell_exec_output());

//build bazar theme
$widget_name = $theme_name;
$widget_path = $theme_dir.$widget_name;
$widget_dist_custom_dest = $dest.'/theme/'.$widget_name;
drush_print('building '.$widget_name.' theme ...kapasoft-drupal-themes');
drush_shell_exec('git clone '.$git_theme_custom_repo.' '.$widget_dist_custom_dest);
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_custom_dest);
print_r(drush_shell_exec_output());

//copy documenation
drush_print('copying documentation ....'.$dest.'/index.html from '.$widget_dist_custom_dest.'/docs/index.html');
drush_shell_exec('sudo sudo cp '.$widget_dist_custom_dest.'/docs/index.html '.$dest.'/index.html');
print_r(drush_shell_exec_output());

//remove git files "sudo rm -rf .git"
drush_print('removing git repo info ....sudo rm -rf '.$dest.'/*/*/.git ');
drush_shell_exec('sudo rm -rf '.$dest.'/*/*/.git');
drush_shell_exec('sudo rm -rf '.$dest.'/*/*/.gitignore');
print_r(drush_shell_exec_output());

//create compressed file
drush_print('compresssing package....');
drush_shell_exec('tar -czf '.$dest.'.tar.gz -C '.$dest_dir.' '.$full_package_name);
print_r(drush_shell_exec_output());

drush_print('Done building '.$package_name.' ver-'.$ver);