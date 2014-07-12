#!/usr/bin/env drush
<?php
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name');
$artifact_type = drush_get_option('artifact-type','module');
$env = drush_get_option('env','test');
if(!isset($artifact_name) || empty($artifact_name) || empty($artifact_type)){drush_die("Artifact name or type not specified");}
//$artifact_dir = 'designssquare_com_module_'.$artifact_name;
//$make_file = '../../config/builds/'.$artifact_type.'-builds/designssquare_com_'.$artifact_name.'_'.$artifact_type.'-'.$env.'.make';
$debug = false;

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);


configure_ckeditor();

//$artifact_dir = drupal_get_path('module', 'designssquare_com_ckeditor');
//$artifact_dir_name = array_pop(explode( "/", $artifact_dir));
//drush_print("dir_name:".$artifact_dir_name);
////if(!file_exists($make_file)){
////    drush_die('make file do note exist');
////}
//
//
////Link CKeditor
//drush_print('Linking CKeditor library....');
//$editor_dest = 'sites/all/libraries/ckeditor';
//if(!file_exists('sites/all/libraries')){
//    drush_print('sites/all/libraries directory does not exist...creating one');
//    drush_shell_exec('sudo mkdir sites/all/libraries');
//    print_r(drush_shell_exec_output());
//}
//
//if(file_exists('sites/all/libraries/ckeditor')){
//    drush_print('sites/all/libraries/ckeditor directory already exist...removing');
//    drush_shell_exec('sudo rm -R sites/all/libraries/ckeditor');
//    print_r(drush_shell_exec_output());
//}
//$editor_src = '../modules/'.$artifact_dir_name.'/libraries/ckeditor';
////$editor_src = $artifact_dir.'/libraries/ckeditor';
//drush_print('linking ckeditor '.$editor_dest.' to path '.$editor_src);
//(symlink($editor_src, $editor_dest)) ? 'linking widget named '.$editor_dest.' to path '.$editor_src : "***ERROR: didn't limked to".$editor_src;
//

//configure audio
//variable_set('audiofield_audioplayer', "wpaudioplayer");
//$payer_audio_src = $artifact_dir."/libraries/player";
//if(!file_exists($payer_audio_src)){
//    drush_die("path to audio player lib does not exist: ".$payer_audio_src, 0);
//}
//variable_set('audiofield_players_dir', $payer_audio_src);
//drush_print('...configured audio player lib to path '.$payer_audio_src);

//move sample data files(images, videos, audios)
//$blog_files_dir = 'sites/all/modules/'.$artifact_dir.'/import/files/';
//drush_print('moving sample data artifacts(i.e images, video files, audio files ...');
//if(!file_exists($blog_files_dir)){
//    drush_die("path to blog files does not exist: ".$blog_files_dir, 0);
//}
//drush_shell_exec('sudo cp -R '.$blog_files_dir.' sites/default/files/');
//print_r(drush_shell_exec_output());


//move sample data files(images, videos, audios) into profile. NOT NEEDED IF FEATURE EXPORT INCLUDES PROFILE IN MODULE
//$blog_profiles_dir = 'sites/all/modules/'.$artifact_dir.'/profiles/';
//drush_print('moving sample data artifacts(i.e images, video files, audio files ...');
//if(!file_exists($blog_profiles_dir)){
//    drush_die("path to blog profiles does not exist: ".$blog_profiles_dir, 0);
//}
//drush_shell_exec('sudo cp -R '.$blog_profiles_dir.' profiles/');
//print_r(drush_shell_exec_output());