#!/usr/bin/env drush
<?php
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name', 'blog');
$artifact_type = drush_get_option('artifact-type', 'module');
$plus_package = drush_get_option('plus', 'no');
$widget_name = 'designssquare_com_' . $artifact_type . '_' . $artifact_name;
$ver = drush_get_option('ver', '0.1');
if($plus_package == 'no'){
    $package_name = 'designssquare-com-' . $artifact_type . '-' . $artifact_name;
}else{
    $package_name = 'designssquare-com-' . $artifact_type . '-' . $artifact_name . '-' . 'plus';
}

$config = get_config();
$dist_dir = $config['dist'];

//@ToDo Fix ssh keys with drush to avoid prompt
$git_mod_repo = 'https://github.com/kapasoft-drupal-modules/' . $artifact_name . '.git';
$git_util_repo = 'https://github.com/kapasoft-config-scripts/';
$git_theme_base_repo = 'git@github.com:kapasoft-drupal-themes/bootstrap.git';
$git_theme_custom_repo = 'https://github.com/kapasoft-drupal-themes/' . $artifact_name . '.git';


$full_package_name = $package_name . '-' . $ver;
$dest_dir = $dist_dir . $artifact_type . 's/' . $widget_name;
$dest = $dest_dir . '/' . $full_package_name;
if (file_exists($dest)) {
    //clean
    drush_print('Build: ' . $package_name . ' with ver:' . $ver . ' exists...deleting');
    drush_shell_exec('sudo rm -R ' . $dest);
    print_r(drush_shell_exec_output());
    //removing old zip file as well
    drush_shell_exec('sudo rm ' . $dest . '.tar.gz');
    print_r(drush_shell_exec_output());
    //removing old zip file as well
    drush_shell_exec('sudo rm ' . $dest . '.zip');
    print_r(drush_shell_exec_output());
}

//build dependent modules
build_modules($artifact_type, $artifact_name, $dest, DEBUG_ON);

////retrieving modules to be included in the build
//$make_file = $make_file = '/Users/maxit/Sites/drupal/config/builds/' . $artifact_type . '-builds/designssquare_com_' . $artifact_name . '_' . $artifact_type . '.make';
//$build_file_parsed = drupal_parse_info_file($make_file);
//$widget_modules = _array_column(array_orderby($build_file_parsed['widget'], 'order', SORT_ASC), "name");
//
///*****DEBUG******/
//if (DEBUG_ON) {
//    drush_print('Modules to include:');
//    print_r($widget_modules);
//}
//
//foreach ($widget_modules as $key => $module) {
//    switch($module){
//        case 'designssquare_lib':
//            $widget_dependency_name = 'designssquare_lib';
//            $widget_dist_dest_lib = $dest . '/modules/designssquare_com_lib';
//            drush_print('building ' . $widget_dependency_name . ' module ...kapasoft-config-scripts');
//            drush_shell_exec('git clone ' . $git_util_repo.'designssquare-utils.git' . ' ' . $widget_dist_dest_lib);
//            print_r(drush_shell_exec_output());
//            break;
//        case  'designssquare_alias_path':
//            $widget_dependency_name = 'designssquare_alias_path';
//            $widget_dist_dest_lib = $dest . '/modules/designssquare_alias_path';
//            drush_print('building ' . $widget_dependency_name . ' module ...kapasoft-config-scripts');
//            drush_shell_exec('git clone ' . $git_util_repo.$widget_dependency_name.'.git' . ' ' . $widget_dist_dest_lib);
//            print_r(drush_shell_exec_output());
//            break;
//        case  'designssquare_alias_path':
//            $widget_dependency_name = 'designssquare_alias_path';
//            $widget_dist_dest_lib = $dest . '/modules/designssquare_alias_path';
//            drush_print('building ' . $widget_dependency_name . ' module ...kapasoft-config-scripts');
//            drush_shell_exec('git clone ' . $git_util_repo.$widget_dependency_name.'.git' . ' ' . $widget_dist_dest_lib);
//            print_r(drush_shell_exec_output());
//            break;
//    }
//}


//build main module
$widget_dist_dest = $dest . '/modules/' . $widget_name;
drush_print('building ' . $widget_name . ' module ...kapasoft-drupal-modules');
if($plus_package == 'yes'){
    drush_shell_exec('git clone -b plus ' . $git_mod_repo . ' ' . $widget_dist_dest);
}else{
    drush_shell_exec('git clone ' . $git_mod_repo . ' ' . $widget_dist_dest);
}
print_r(drush_shell_exec_output());

//build designssqure common module
//$ds_lib_included = drush_get_option('ds-lib', 'no');
//if ($ds_lib_included == 'yes') {
//    $widget_name = 'designssquare_lib';
//    $widget_dist_dest_lib = $dest . '/modules/designssquare_com_lib';
//    drush_print('building ' . $widget_name . ' module ...kapasoft-config-scripts');
//    drush_shell_exec('git clone ' . $git_util_repo . ' ' . $widget_dist_dest_lib);
//    print_r(drush_shell_exec_output());
//}

//@ToDo make this as choice since all themes don't need bootstrap
//build base theme
$ds_lib_included = drush_get_option('base-theme', 'no');
if ($ds_lib_included == 'yes') {
    $widget_name = 'bootstrap';
    $widget_dist_base_dest = $dest . '/themes/' . $widget_name;
    drush_print('building ' . $widget_name . ' theme ...');
    drush_shell_exec('git clone ' . $git_theme_base_repo . ' ' . $widget_dist_base_dest);
    print_r(drush_shell_exec_output());
}

//build  theme
$widget_name = $artifact_name;
$widget_dist_custom_dest = $dest . '/themes/' . $widget_name;
drush_print('building ' . $widget_name . ' theme ...kapasoft-drupal-themes');
drush_shell_exec('git clone ' . $git_theme_custom_repo . ' ' . $widget_dist_custom_dest);
print_r(drush_shell_exec_output());


//copy documentation
//drush_print('copying documentation ....' . $dest . '/docs/* from ' . $widget_dist_custom_dest . '/docs/*');
//drush_shell_exec('sudo mkdir ' . $dest . '/docs/');
//print_r(drush_shell_exec_output());
//drush_shell_exec('sudo sudo cp -R ' . $widget_dist_custom_dest . '/docs/* ' . $dest . '/docs/');
//print_r(drush_shell_exec_output());
_copy_documentation($widget_dist_custom_dest, $dest);

//remove git files "sudo rm -rf .git"
drush_print('removing git repo info ....sudo rm -rf ' . $dest . '/*/*/.git ');
drush_shell_exec('sudo rm -rf ' . $dest . '/*/*/.git');
drush_shell_exec('sudo rm -rf ' . $dest . '/*/*/.gitignore');
print_r(drush_shell_exec_output());

//create compressed file
drush_print('compresssing package....');
drush_shell_exec('tar -czf ' . $dest . '.tar.gz -C ' . $dest_dir . ' ' . $full_package_name);
print_r(drush_shell_exec_output());

//create zip file
drush_print('ziping package....');
//to avoid full path, we go to distribution. this can be improved
drush_op('chdir', $dest_dir);
drush_shell_exec('zip -r ' . $full_package_name . '.zip ' . $full_package_name);

drush_print('Done building ' . $package_name . ' ver-' . $ver);