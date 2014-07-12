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

configure_audio('designssquare_com_widget_podcast');