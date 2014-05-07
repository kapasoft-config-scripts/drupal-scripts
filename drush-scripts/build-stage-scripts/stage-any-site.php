#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';

$artifact_name = drush_get_option('artifact-name','blog');
$artifact_type = drush_get_option('artifact-type','site');
$dest_dir = ($artifact_type == 'theme') ? 'sites/all/themes' : 'sites/all/modules';
$debug = true;
//@ToDo Fix ssh keys with drush to avoid prompt
$git_source_repo = 'https://github.com/kapasoft-drupal-site-modules/'.$artifact_name.'.git';


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
$widget_dest = $dest_dir.'/'.'designssquare_com_'.$artifact_type.'_'.$artifact_name;
if(is_dir($widget_dest)){
    drush_print('Removing module '.$artifact_name.' before staging...');
    drush_shell_exec('sudo rm -R '.$widget_dest);
    print_r(drush_shell_exec_output());
}
drush_print('staging '.$artifact_name.' ...'.$git_source_repo);
drush_shell_exec('git clone '.$git_source_repo.' '.$widget_dest);
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

