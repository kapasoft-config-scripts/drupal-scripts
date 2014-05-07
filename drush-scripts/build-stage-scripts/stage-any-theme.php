#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';

$artifact_name = drush_get_option('artifact-name','blog');
$artifact_type = drush_get_option('artifact-type','theme');
$dest_theme_dir = 'sites/all/themes';
$debug = true;
//@ToDo Fix ssh keys with drush to avoid prompt
$git_theme_base_repo = 'https://github.com/kapasoft-drupal-themes/bootstrap.git';
//$git_theme_custom_repo = 'https://github.com/kapasoft-drupal-themes/calypso.git';
$git_theme_custom_repo = 'https://github.com/kapasoft-drupal-'.$artifact_type.'s/'.$artifact_name.'.git';


// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

//build modules
stage_modules($artifact_type, $artifact_name, $dest, $debug);

//build base theme
$widget_name = 'bootstrap';
$widget_dist_custom_dest = $dest_theme_dir.'/'.$widget_name;
if(is_dir($widget_dist_custom_dest)){
    drush_print('Removing theme '.$widget_name.' before staging...');
    drush_shell_exec('sudo rm -R '.$widget_dist_custom_dest);
    print_r(drush_shell_exec_output());
}
drush_print('building '.$widget_name.' theme ...kapasoft-drupal-themes');
drush_shell_exec('git clone '.$git_theme_base_repo.' '.$widget_dist_custom_dest);
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_custom_dest);
print_r(drush_shell_exec_output());

//build custom theme
$widget_name = $artifact_name;
$widget_dist_custom_dest = $dest_theme_dir.'/'.$widget_name;
if(is_dir($widget_dist_custom_dest)){
    drush_print('Removing theme '.$widget_name.' before staging...');
    drush_shell_exec('sudo rm -R '.$widget_dist_custom_dest);
    print_r(drush_shell_exec_output());
}
drush_print('building '.$widget_name.' theme ...kapasoft-drupal-themes');
drush_shell_exec('git clone '.$git_theme_custom_repo.' '.$widget_dist_custom_dest);
//drush_shell_exec('sudo cp -R '.$widget_path.'/ '.$widget_dist_custom_dest);
print_r(drush_shell_exec_output());

//copy documentation
//drush_print('copying documentation from '.$widget_dist_custom_dest.'/docs ...');
//if(!file_exists('docs')){
//    drush_print('docs folder missing ...creating one');
//    drush_shell_exec('mkdir docs');
//    print_r(drush_shell_exec_output());
// }
//drush_shell_exec('sudo cp -r '.$widget_dist_custom_dest.'/docs/ docs/*');
//print_r(drush_shell_exec_output());

