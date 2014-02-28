#!/usr/bin/env drush
<?php
require_once  '../config/drush-scripts/includes/drush-script-extensions.inc';


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

drush_print('ENABLING DEPENDENT MODULES ...');
foreach($all_dependent_modules as $key => $dependent_module){
    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}


//enable widget modules
drush_print('ENABLING WIDGET MODULES ...');
foreach($widget_modules as $key => $widget_module){
    drush_print('widget module '. $widget_module . ((drush_invoke_process("@self", "pm-enable", array($widget_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}


drush_print('Done');