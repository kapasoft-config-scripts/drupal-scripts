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


# Enable modules
##########################################################
$build_file_parsed = drupal_parse_info_file($make_file);
//print_r($build_file_parsed);
$modules_to_enable = array_diff($build_file_parsed['projects'],array('drupal'));
//print_r($modules_to_enable);
$modules_enabled = drush_invoke_process("@self", "pm-enable", $modules_to_enable);
drush_print('Modules ' . (($modules_enabled) ? 'WAS' : 'WAS NOT' ). '  Enabled');


#ToDo Enable Fe Block(fe_block_settings)
#ToDo Enable Contact(contact)
#ToDo Enable admin_menu

# Pre configure settings
##########################################################
# disable user pictures
variable_set('user_pictures',0);

# allow only admins to register users
variable_set('user_register',0);

# set site slogan
variable_set('site_slogan',$siteSlogan);

# Configure JQuery update
variable_set('jquery_update_compression_type', "min");
variable_set('jquery_update_jquery_cdn',"google");
variable_set('jquery_update_jquery_version',"1.7");

//make public dir
drush_shell_exec('sudo mkdir sites/default/files');
drush_shell_exec('sudo chmod -R 777 sites/default/files');
