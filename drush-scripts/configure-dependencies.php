#!/usr/bin/env drush
<?php
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name');
$artifact_type = drush_get_option('artifact-type','module');
$env = drush_get_option('env','test');
//constraints
if(!isset($artifact_name) || empty($artifact_name)){drush_die("Artifact name not specified");}
//$package_name = 'designssquare-com-'.$artifact_type.'-'.$artifact_name;
//$artifact_dir = 'designssquare_com_'.$artifact_name;
//$make_file = '../../config/builds/'.$artifact_type.'-builds/designssquare_com_'.$artifact_name.'_'.$artifact_type.'.make';
$make_file = get_make_file($artifact_name, $artifact_type);
drush_print('****makefile: '.$make_file);
$debug = false;

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

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
}else{
    drush_print('Widget Modules:');
    print_r($widget_modules);
}


if($env == 'dev'){
//@ToDo grab the dev modules from make file perhaps
//Adding Dev modules
$all_dependent_modules[] = 'module_filter';
$all_dependent_modules[] = 'devel';
////@ToDo simplehtmldom now requires lib to copied https://drupal.org/node/1645932
$all_dependent_modules[] = 'simplehtmldom';
$all_dependent_modules[] = 'devel_themer';
$all_dependent_modules[] = 'coffee';
$all_dependent_modules[] = 'admin_menu';
$all_dependent_modules[] = 'features_diff';
$all_dependent_modules[] = 'jquery_update';
}

//remove widget modules for being enabled
$dependencies_without_widgets = array_diff($all_dependent_modules, $widget_modules);
drush_print('ENABLING DEPENDENT MODULES ...');
//print_r($dependencies_without_widgets);


//retrieving inactive dependencies
//$currently_disabled = drush_invoke_process("@self", "pm-list",array(), array('pipe'=>'yes','no-core'=>'yes','type' =>'module', 'status' => 'Disabled')) ? : array();
//$currently_uninstalled = drush_invoke_process("@self", "pm-list",array(), array('pipe'=>'yes','no-core'=>'yes','type' =>'module', 'status' => 'Not installed')) ? : array();
//$cur_disabled= empty($currently_disabled['object']) ? array() : $currently_disabled['object'];
//$cur_uninstalled = empty($currently_uninstalled['object']) ? array() : $currently_uninstalled['object'];
//$inactive_dependencies = array_merge(array_keys($cur_disabled), array_keys($cur_uninstalled));
//$depenencies_not_enabled = array_intersect($dependencies_without_widgets, $inactive_dependencies);


//make only the ones disabled or uninstalled to be enabled
$currently_enabled = drush_invoke_process("@self", "pm-list",array(), array('pipe'=>'yes','type' =>'module', 'status' => 'Enabled')) ? : array();
$cur_enabled = empty($currently_enabled['object']) ? array() : array_keys($currently_enabled['object']);
$all_not_enabled = array_diff($dependencies_without_widgets, $cur_enabled);
//$depenencies_not_enabled = array_intersect(array_values($all_not_enabled), array_values($all_dependent_modules));
$depenencies_not_enabled = array_intersect($all_not_enabled, $all_dependent_modules);

if($debug){
    drush_print('Cur Enabled');
    print_r($cur_enabled);
    drush_print('All Not Enabled');
    print_r($all_not_enabled);
    drush_print('Currently Inactive From Dependencies');
    print_r($depenencies_not_enabled);
}else{
    drush_print('Currently Inactive From Dependencies');
    print_r($depenencies_not_enabled);
}

//enable inactive depencencies
foreach($depenencies_not_enabled as $key => $dependent_module){
    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}