#!/usr/bin/env drush
<?php
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

drush_print('executing action:add_user');
/******API
 * --user:
 * --mail:
 * --password:
 * --role:
 */

$debug = true;

$user_name = drush_get_option('user-name', FALSE);
$mail = drush_get_option('mail',FALSE);
$password = drush_get_option('password',FALSE);
$role = drush_get_option('role',FALSE);
if(!($user_name || $mail || $password || $role)){
    drush_die("Params are not correctly specified"."\n".
    "--user-name:".$user_name."\n".
    "--mail:".$mail."\n".
    "--password:".$password."\n".
    "--role:".$role."\n"
    );
}

if($debug){
    drush_print('user-name: '.$user_name);
    drush_print('mail: '.$mail);
    drush_print('password: '.$password);
    drush_print('role: '.$role);
}

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);


add_user($user_name);