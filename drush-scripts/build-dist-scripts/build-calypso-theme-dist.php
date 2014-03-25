#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name','calypso');
$artifact_type = drush_get_option('artifact_type','theme');
$widget_name = 'designssquare_com_'.$artifact_name;
$ver = drush_get_option('ver','0.2');
$package_name = 'designssquare-com-'.$artifact_type.'-'.$artifact_name;
$module_dir = '/Users/maxit/Sites/drupal/modules/';
$theme_dir = '/Users/maxit/Sites/drupal/themes/';
$dist_dir = '/Users/maxit/Sites/drupal/dist/';
$debug = true;
//@ToDo Fix ssh keys with drush to avoid prompt
$git_mod_repo = 'https://github.com/kapasoft-drupal-modules/'.$artifact_name.'.git';
$git_util_repo = 'https://github.com/kapasoft-config-scripts/designssquare-utils.git';
$git_theme_base_repo = 'git@github.com:kapasoft-drupal-themes/bootstrap.git';
$git_theme_custom_repo = 'https://github.com/kapasoft-drupal-themes/'.$artifact_name.'.git';

$full_package_name = $package_name.'-'.$ver;
$dest_dir = $dist_dir.$artifact_type.'s/'.$widget_name;
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

//build modules
$widget_dist_dest = $dest.'/modules/'.$widget_name;
drush_print('building '.$widget_name.' module ...kapasoft-drupal-modules');
drush_shell_exec('git clone '.$git_mod_repo.' '.$widget_dist_dest);
print_r(drush_shell_exec_output());

//build designssqure common module
$widget_name = 'designssquare_lib';
$widget_dist_dest_lib = $dest.'/modules/designssquare_lib';
drush_print('building '.$widget_name.' module ...kapasoft-config-scripts');
drush_shell_exec('git clone '.$git_util_repo.' '.$widget_dist_dest_lib);
print_r(drush_shell_exec_output());


//build base theme
$widget_name = 'bootstrap';
$widget_dist_base_dest = $dest.'/themes/'.$widget_name;
drush_print('building '.$widget_name.' theme ...');
drush_shell_exec('git clone '.$git_theme_base_repo.' '.$widget_dist_base_dest);
print_r(drush_shell_exec_output());

//build bazar theme
$widget_name = $artifact_name;
$widget_dist_custom_dest = $dest.'/themes/'.$widget_name;
drush_print('building '.$widget_name.' theme ...kapasoft-drupal-themes');
drush_shell_exec('git clone '.$git_theme_custom_repo.' '.$widget_dist_custom_dest);
print_r(drush_shell_exec_output());

//copy documenation
$documentation_location = ($artifact_type == 'module' ) ? $widget_dist_dest : $widget_dist_custom_dest;
drush_print('copying documentation ....'.$dest.'/index.html from '.$documentation_location.'/docs/index.html');
drush_shell_exec('sudo sudo cp '.$documentation_location.'/docs/index.html '.$dest.'/index.html');
print_r(drush_shell_exec_output());

//remove git files "sudo rm -rf .git"
drush_print('removing git repo info ....sudo rm -rf '.$dest.'/*/*/.git ');
drush_shell_exec('sudo rm -rf '.$dest.'/*/*/.git');
drush_shell_exec('sudo rm -rf '.$dest.'/*/*/.gitignore');
print_r(drush_shell_exec_output());

//create compressed file
drush_print('compresssing package: tar.gz....');
drush_shell_exec('tar -czf '.$dest.'.tar.gz -C '.$dest_dir.' '.$full_package_name);
print_r(drush_shell_exec_output());

//create zip file
drush_print('ziping package....');
//to avoid full path, we go to distribution. this can be improved
drush_op('chdir', $dest_dir);
drush_shell_exec('zip -r '.$full_package_name.'.zip '.$full_package_name);
print_r(drush_shell_exec_output());

drush_print('Done building '.$package_name.' ver-'.$ver);