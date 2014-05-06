#!/usr/bin/env drush
<?php
require_once  getcwd().'/../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$project_name = 'bazar';
$ver = '0.2';
$module_dir = 'sites/all/modules';
$theme_dir = 'sites/all/themes';
$src_dir = '/Users/maxit/Sites/drupal';
$debug = true;


$module_src = $src_dir.'/modules/'.$module_name;
$theme_src = $src_dir.'/themes';
$make_file = '/Users/maxit/Sites/drupal/config/theme-builds/designssquare_com_bazar_theme-dev.make';


//$src_dist = $src_dir.'themes/'.$theme_name.'/'.$package_name.'-'.$ver;

drush_print('changing permissions to writable...');
drush_shell_exec('sudo chmod -R 777 .');
print_r(drush_shell_exec_output());


//link to widget
drush_print('Linking modules....');

$widget_name = 'sites/all/modules/'.$module_name;
if(!file_exists($module_src)){
    drush_die("path to source module does not exist: ".$module_src, 0);
}
drush_print('linking modules named '.$widget_name.' to path '.$module_src);
(symlink($module_src, $widget_name)) ? 'linking widget named '.$widget_name.' to path '.$module_src : "***ERROR: didn't limked to".$path_to_module;

//linking base theme
//drush_print('Linking themes....');
//$theme_name = 'bootstrap';
//$theme_src_path = $theme_src.'/'.$theme_name;
//$theme_dest_path = $theme_dir.'/'.$theme_name;
//if(!file_exists($theme_src_path)){
//    drush_die("path to theme source does not exist: ".$theme_src_path, 0);
//}
//drush_print('linking theme '.$theme_dest_path.' to path '.$theme_src_path);
//(symlink($theme_src_path, $theme_dest_path)) ? 'linking widget named '.$theme_dest_path.' to path '.$theme_src_path : "***ERROR: didn't linked to".$theme_src_path;

//linking custom theme
$theme_name = $project_name;
$theme_src_path = $theme_src.'/'.$theme_name;
$theme_dest_path = $theme_dir.'/'.$theme_name;
if(!file_exists($theme_src_path)){
    drush_die("path to theme source does not exist: ".$theme_src_path, 0);
}
drush_print('linking theme '.$theme_dest_path.' to path '.$theme_src_path);
(symlink($theme_src_path, $theme_dest_path)) ? 'linking widget named '.$theme_dest_path.' to path '.$theme_src_path : "***ERROR: didn't linked to".$theme_src_path;

if(!file_exists($make_file)){
    drush_die("the make file does not exist: ".$make_file, 0);
}


// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);


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
$all_dependent_modules[] = 'devel';
$all_dependent_modules[] = 'simplehtmldom';
$all_dependent_modules[] = 'devel_themer';
$all_dependent_modules[] = 'coffee';
$all_dependent_modules[] = 'admin_menu';
$all_dependent_modules[] = 'features_diff';
$all_dependent_modules[] = 'jquery_update';
//$all_dependent_modules[] = 'media';

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


//set local private file cache location admin/config/media/file-system