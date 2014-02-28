#!/usr/bin/env drush
<?php
/****SAMPLE****/
//sudo drush ../config/drush-scripts/install-widget.php --build-src="../config/mod-builds/designssquare_com_blog.make" --no-core --contrib-destination=sites/all --debug

require_once  '../config/drush-scripts/includes/drush-script-extensions.inc';

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

drush_print("Time to prepare the working environment.");

// let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

drush_include_engine('drupal', 'environment', drush_drupal_major_version());

//download widget
drush_print('Downloading widgets as specified in '.$make_file);
$status = _process_make_file($make_file);
drush_print('Make file download '.(($status) ? "SUCCESS" : "FAILURE" ));

//$exec_config_script = 'drush ../config/drush-scripts/configure-widget.php';
//$exec_config_script .= (drush_get_option('debug', FALSE)) ? ' --debug ' : '';
//$exec_config_script .= ' --build-src='.$make_file;
//drush_shell_exec($exec_config_script);






//print_r(drush_shell_exec_output());


//foreach($dependency_status as $key => $module){
//    foreach($module['unmet-dependencies'] as $key=>$missing_module ){
//        $missing_dependent_modules[] = $missing_module;
//    }
//    $all_dependent_modules = array_merge($all_dependent_modules,$module['dependencies']);
//}

////DEBUGING Purpose
////drush_print('/****** MISSING DEPENDENT MODULES *******/');
////print_r($missing_dependent_modules);
//drush_print('/****** ALL DEPENDENT MODUlES *******/');
//print_r($all_dependent_modules);


//drush_print('REPORT: '.count($missing_dependent_modules) . ' is missing dependent modules from total of ' . count($all_dependent_modules). ' dependant modules');
//
//if (drush_get_option('dl-dependencies', FALSE)) {
////download missing modules
//    drush_print('DOWNLOADING MISSING MODULES ...');
//    foreach($missing_dependent_modules as $key => $missing_module){
//        drush_print('module '. $missing_module . ((drush_invoke_process('@self', 'dl', array($missing_module)) ? ' WAS ' : ' WAS NOT')) . ' downloaded');
//    }
//}




////enable dependencies
//drush_print('ENABLING DEPENDENT MODULES ...');
//foreach($all_dependent_modules as $key => $dependent_module){
//    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
//}
//
////enable widget modules
//drush_print('ENABLING WIDGET MODULES ...');
//foreach($widget_modules as $key => $widget_module){
//    drush_print('widget module '. $widget_module . ((drush_invoke_process("@self", "pm-enable", array($widget_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
//}
//
//drush_print('Done');
//
//
///**
// * Make a database backup
// */
//function _backup_stuff() {
//    // backup the db
//    $ts = date('Ymdhis');
//    //@ToDo fix drush_invoke_process to work
    //(drush_invoke_process('@self', 'sql-dump', array('result-file' =>  'revert_backup.sql'))) ? drush_print('back up success') : drush_print('no back up') ;
//    drush_shell_exec('drush sql-dump --result-file=drush_backup/revert_'.$ts.'_backup.sql --gzip=TRUE');
//}