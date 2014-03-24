#!/usr/bin/env drush
<?php
require_once  '../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$theme_name = 'bazar';
$package_name = 'designssquare-com-theme-bazar';
$module_dir = 'sites/all/modules';
$theme_dir = 'sites/all/themes';
$dist_dir = '/Users/maxit/Sites/drupal/dist/';
$make_file = '/Users/maxit/Sites/drupal/config/builds/theme-builds/designssquare_com_bazar_theme-test.make';
$debug = true;

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

drush_print('changing permissions to writable...');
drush_shell_exec('sudo chmod -R 777 .');
print_r(drush_shell_exec_output());

if(!file_exists($make_file)){
    drush_die('make file do note exist');
}

//initilize
$build_file_parsed = drupal_parse_info_file($make_file);
$widget_modules = _array_column(array_orderby($build_file_parsed['widget'], 'order', SORT_ASC),"name");
$all_dependent_modules = array();
$missing_dependent_modules = array();

//retrieving dependency list
drush_include_engine('drupal', 'environment', drush_drupal_major_version());
$module_info =  drush_get_modules();
$dependency_status = drush_check_module_dependencies($widget_modules, $module_info);
$all_dependent_modules = array();
foreach(_array_column($dependency_status,'dependencies') as $key => $mod_dependancies){
    $all_dependent_modules = array_merge($all_dependent_modules,$mod_dependancies);
}

/*****DEBUG******/
if($debug){
    drush_print('Widget Modules:');
    print_r($widget_modules);
    drush_print('Dependency STatus:');
    print_r($dependency_status);
    drush_print('All Dependent MODULES');
    print_r($all_dependent_modules);
}



//@ToDo grab the dev modules from make file perhaps
//Adding Dev modules
$all_dependent_modules[] = 'module_filter';
//$all_dependent_modules[] = 'devel';
//@ToDo simplehtmldom now requires lib to copied https://drupal.org/node/1645932
$all_dependent_modules[] = 'simplehtmldom';
$all_dependent_modules[] = 'devel_themer';
$all_dependent_modules[] = 'coffee';
$all_dependent_modules[] = 'admin_menu';
$all_dependent_modules[] = 'features_diff';
$all_dependent_modules[] = 'jquery_update';

//remove widget modules for being enabled
$dependencies_without_widgets = array_diff($all_dependent_modules,$widget_modules);
drush_print('ENABLING DEPENDENT MODULES ...');
foreach($dependencies_without_widgets as $key => $dependent_module){
    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}

drush_print('configuring jquery_update...');
variable_set('jquery_update_compression_type', "min");
variable_set('jquery_update_jquery_cdn',"google");
variable_set('jquery_update_jquery_admin_version',"1.10");
variable_set('jquery_update_jquery_version',"1.10");

//make public dir
drush_shell_exec('sudo mkdir sites/default/files');
drush_shell_exec('sudo chmod -R 777 sites/default/files');


//installing simplehtmldom
$drup_lib = 'sites/all/libraries';
if(!file_exists($drup_lib)){
    drush_print('Folder - '.$drup_lib.' does not exists.... creating one...');
    drush_shell_exec('sudo mkdir '.$drup_lib);
}
drush_shell_exec('sudo ln -s /Users/maxit/Sites/drupal/other/simplehtmldom sites/all/libraries/simplehtmldom');