#!/usr/bin/env drush
<?php
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name');
$artifact_type = drush_get_option('artifact-type','module');
$module_dir = 'sites/all/modules';
$theme_dir = 'sites/all/themes';
$src_dir = '/Users/maxit/Sites/drupal';
$debug = true;


// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

drush_print('configuring jquery_update...');
variable_set('jquery_update_compression_type', "min");
variable_set('jquery_update_jquery_cdn',"google");
variable_set('jquery_update_jquery_admin_version',"1.10");
variable_set('jquery_update_jquery_version',"1.10");

//make public dir
drush_shell_exec('sudo mkdir sites/default/files');
drush_shell_exec('sudo chmod -R 777 sites/default/files');


//@ToDo set local private file cache location admin/config/media/file-system

//@ToDo enable and set default Bazar theme

drush_print('Done configuring '.$artifact_type.' - '.$artifact_name);
