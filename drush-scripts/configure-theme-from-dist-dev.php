#!/usr/bin/env drush
<?php
require_once  '../config/drush-scripts/includes/drush-script-extensions.inc';

/**** Command Options
 *************
 * widget-path ;path to locally drupal module to link to
 * theme-name ;name of the theme to test
 * widget-path ;source path to drupal module to link to
 * widget-name ;name of the widget to test
****/

$ver = '0.2';
$package_name = 'designssquare-com-bazar-theme';
$theme_name = 'bazar';
//$theme_dir = '/Users/maxit/Sites/drupal/themes/';
$src_dir = '/Users/maxit/Sites/drupal/dist/themes/'.$theme_name.'/'.$package_name.'-'.$ver;

$module_name = drush_get_option('widget-name', "test-widget");
$module_src =  $src_dir.'/modules';


$theme_dest_path = 'sites/all/themes/'.$theme_name;
$theme_src_path = $src_dir.'/theme/';

$make_file = '../config/theme-builds/designssquare_com_bazar_theme.make';

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);


//link to widget
drush_print('Linking modules....');
$widget_name = 'sites/all/modules/'.$module_name;
if(!file_exists($module_src)){
    drush_die("path to source module does not exist: ".$module_src, 0);
}
drush_print('linking modules named '.$widget_name.' to path '.$module_src);
(symlink($module_src, $widget_name)) ? 'linking widget named '.$widget_name.' to path '.$module_src : "***ERROR: didn't limked to".$path_to_module;

drush_print('Linking theme....');
if(!file_exists($theme_src_path)){
    drush_die("path to theme source does not exist: ".$theme_src_path, 0);
}
drush_print('linking theme named '.$widget_name.' to path '.$theme_src_path);
(symlink($theme_src_path, $theme_dest_path)) ? 'linking widget named '.$theme_dest_path.' to path '.$theme_src_path : "***ERROR: didn't linked to".$theme_src_path;


if(!file_exists($make_file)){
    drush_die("the make file does not exist: ".$make_file, 0);
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
//drush_print('Widget Modules:');
//print_r($widget_modules);
//drush_print('Dependency STatus:');
//print_r($dependency_status);
drush_print('All Dependent MODULES');
print_r($all_dependent_modules);

//Adding Dev modules
$all_dependent_modules[] = 'module_filter';
$all_dependent_modules[] = 'devel';
$all_dependent_modules[] = 'simplehtmldom';
$all_dependent_modules[] = 'devel_themer';
$all_dependent_modules[] = 'coffee';
$all_dependent_modules[] = 'admin_menu';
$all_dependent_modules[] = 'features_diff';
$all_dependent_modules[] = 'jquery_update';

drush_print('ENABLING DEPENDENT MODULES ...');
foreach($all_dependent_modules as $key => $dependent_module){
    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}

//set permission for devel_theme
drush_shell_exec('sudo chmod -R 777 /private/tmp/devel_themer');


//link JW Player library
drush_shell_exec('sudo mkdir sites/all/libraries');
//link to library
$library_name = 'sites/all/libraries/jwplayer';
$path_to_library =  'sites/all/modules/'.$module_name.'/designssquare_com_blog/libraries/jwplayer';
drush_print('linking widget named '.$library_name.' to path '.$path_to_library);
(symlink($path_to_library, $library_name)) ? 'linking widget named '.$library_name.' to path '.$path_to_library : "***ERROR: didn't like to ".$path_to_library;


//@ToDo Copy Blog files

//for blog
//'chmod -R 777 ../text-core'
//'drush dl uuid-7.x-1.x-dev'
//'drush en uuid'
//'drush dl uuid_features-7.x-1.x-dev '
//'drush en uuid_features'

# Configure JQuery update
variable_set('jquery_update_compression_type', "min");
variable_set('jquery_update_jquery_cdn',"google");
variable_set('jquery_update_jquery_version',"1.7");

//make public dir
drush_shell_exec('sudo mkdir sites/default/files');
drush_shell_exec('sudo chmod -R 777 sites/default/files');

// let's jump outside site directory to set permissions so editors can save
drush_op('chdir', '..');
drush_shell_exec('sudo chmod -R 777 .');


//enable widget modules
//drush_print('ENABLING WIDGET MODULES ...');
//foreach($widget_modules as $key => $widget_module){
//    drush_print('widget module '. $widget_module . ((drush_invoke_process("@self", "pm-enable", array($widget_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
//}

//$widget_name = 'sites/all/modules/'.drush_get_option('widget-name', "test-widget");
//$path_to_module =  '~/Sites/drupal/modules/'.drush_get_option('widget-path', "test-widget");

//link to widget
//drush_print('linking widget named '.$widget_name.' to path '.$path_to_module);
//(symlink($widget_name, $path_to_module)) ? 'linking widget named '.$widget_name.' to path '.$path_to_module : "***ERROR: didn't like to".$path_to_module;
//drush_shell_exec('sudo ln -s '.$path_to_module.' '.$widget_name);

drush_print('Done');