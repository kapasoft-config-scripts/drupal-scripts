#!/usr/bin/env drush
<?php
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name');
$artifact_type = drush_get_option('artifact-type','module');
$env = drush_get_option('env','test');
if(!isset($artifact_name) || empty($artifact_name) || empty($artifact_type)){drush_die("Artifact name or type not specified");}
$artifact_dir = 'designssquare_com_module_'.$artifact_name;
$make_file = '../../config/builds/'.$artifact_type.'-builds/designssquare_com_'.$artifact_name.'_'.$artifact_type.'-'.$env.'.make';
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
}



//@ToDo grab the dev modules from make file perhaps
//Adding Dev modules
//$all_dependent_modules[] = 'module_filter';
////$all_dependent_modules[] = 'devel';
////@ToDo simplehtmldom now requires lib to copied https://drupal.org/node/1645932
//$all_dependent_modules[] = 'simplehtmldom';
//$all_dependent_modules[] = 'devel_themer';
//$all_dependent_modules[] = 'coffee';
//$all_dependent_modules[] = 'admin_menu';
//$all_dependent_modules[] = 'features_diff';
//$all_dependent_modules[] = 'jquery_update';
//$all_dependent_modules[] = 'media';

//remove widget modules for being enabled
$dependencies_without_widgets = array_diff($all_dependent_modules, $widget_modules);
drush_print('ENABLING DEPENDENT MODULES ...');
foreach($dependencies_without_widgets as $key => $dependent_module){
    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}

//Link VideoJs
drush_print('Linking videojs....');
$player_dest = 'sites/all/libraries/video-js';
if(!file_exists('sites/all/libraries')){
    drush_print('sites/all/libraries directory does not exist...creating one');
    drush_shell_exec('sudo mkdir sites/all/libraries');
    print_r(drush_shell_exec_output());
}
$payer_src = '../modules/'.$artifact_dir.'/libraries/video-js';
drush_print('linking player '.$player_dest.' to path '.$payer_src);
//sudo ln -s ../modules/designssquare_com_blog/libraries/video-js sites/all/libraries/video-js
(symlink($payer_src, $player_dest)) ? 'linking widget named '.$player_dest.' to path '.$payer_src : "***ERROR: didn't limked to".$payer_src;

//configure audio
variable_set('audiofield_audioplayer', "wpaudioplayer");
$payer_audio_src = "sites/all/modules/$artifact_dir/libraries/player";
if(!file_exists($payer_audio_src)){
    drush_die("path to audo player lib does not exist: ".$payer_audio_src, 0);
}
variable_set('audiofield_players_dir', $payer_audio_src);
drush_print('...configured audio player lib to path '.$payer_audio_src);

//move sample data files(images, videos, audios)
//$blog_files_dir = 'sites/all/modules/'.$artifact_dir.'/import/files/';
//drush_print('moving sample data artifacts(i.e images, video files, audio files ...');
//if(!file_exists($blog_files_dir)){
//    drush_die("path to blog files does not exist: ".$blog_files_dir, 0);
//}
//drush_shell_exec('sudo cp -R '.$blog_files_dir.' sites/default/files/');
//print_r(drush_shell_exec_output());


//move sample data files(images, videos, audios) into profile. NOT NEEDED IF FEATURE EXPORT INCLUDES PROFILE IN MODULE
$blog_profiles_dir = 'sites/all/modules/'.$artifact_dir.'/profiles/';
drush_print('moving sample data artifacts(i.e images, video files, audio files ...');
if(!file_exists($blog_profiles_dir)){
    drush_die("path to blog profiles does not exist: ".$blog_profiles_dir, 0);
}
drush_shell_exec('sudo cp -R '.$blog_profiles_dir.' profiles/');
print_r(drush_shell_exec_output());