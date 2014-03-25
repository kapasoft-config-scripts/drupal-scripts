#!/usr/bin/env drush
<?php
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$module_dir = 'sites/all/modules';
$theme_dir = 'sites/all/themes';
$make_file = '../../config/builds/theme-builds/designssquare_com_calypso_theme-stage.make';
$debug = true;

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

//drush_print('changing permissions to writable...');
//drush_shell_exec('sudo chmod -R 777 .');
//print_r(drush_shell_exec_output());

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
$dependencies_per_widget = _array_column($dependency_status,'dependencies');
foreach($dependencies_per_widget as $key => $mod_dependancies){
    $all_dependent_modules = (isset($mod_dependancies) && is_array($mod_dependancies)) ? array_unique(array_merge($all_dependent_modules, $mod_dependancies)) : $all_dependent_modules;
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

//remove widget modules for being enabled
$dependencies_without_widgets = array_diff($all_dependent_modules, $widget_modules);

if($debug){
    drush_print('All Dependent MODULES without artifacts before enabling....');
    print_r($dependencies_without_widgets);
}

drush_print('ENABLING DEPENDENT MODULES ...');
foreach($dependencies_without_widgets as $key => $dependent_module){
    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}

drush_print('configuring jquery_update...');
variable_set('jquery_update_compression_type', "min");
variable_set('jquery_update_jquery_cdn',"google");
variable_set('jquery_update_jquery_admin_version',"1.8");
variable_set('jquery_update_jquery_version',"1.8");

//make public dir
drush_shell_exec('sudo mkdir sites/default/files');
drush_shell_exec('sudo chmod -R 777 sites/default/files');
