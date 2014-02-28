#!/usr/bin/env drush
<?php
require_once  '../config/drush-scripts/includes/drush-script-extensions.inc';

drush_print('GOT HeRE');
/*******Available Params*********/
#'build-src' :: path to the make file(default is ../builds/build-drupal-core.make)
#'build-dest' :: name of the dir to install site relative to current dir (default is 'test-core')
#'db-name' :: db name (default 'drupal_test_db')
#'db-user' :: db user (default 'test_user')
#'db-pass' :: db pass (default '38394815')
# Drush options :: debug, no-core, contrib-destination
# Example: sudo drush ../config/drush-scripts/configure-site.php --debug --build-src=../config/builds/build-drupal-core.make
#sudo drush config/drush-scripts/install_site.php --debug --build-dest=test-core --build-src=config/builds/build-drupal-core.make


#Project Configurations
################
####CALYPSO
//$project = 'test-calypso';
//$db_name = 'test_calypso_db';
//$db_user = 'makapacs';
//$db_password = '38394815';

####CASCADE-test
//$project = 'test-cascade';
//$db_name = 'test_cascade_db';
//$db_user = 'test_cascade';
//$db_password = '38394815';

####CASCADE-dev
$project = 'cascade';
$db_name = 'cascade_dev';
//$db_name = $project.'_db';
$db_user = $project.'_user';
$db_password = 'password';
$debug_mode = TRUE;

# Site Configurations
##########################################################
$AdminUsername="admin";
$AdminPassword="password";
$adminEmail="admin@example.com";
$siteName= $project." Site";
$siteSlogan="developing ". $project ." theme";
$siteLocale="en";
##########################################################

# Database
##########################################################
$dbHost="localhost";
$dbName= ($dbname = drush_get_option('db-name', FALSE)) ? $dbname : $db_name;
$dbUser= ($dbuser = drush_get_option('db-user', FALSE)) ? $dbuser : $db_user;
$dbPassword = ($dbpass = drush_get_option('db-pass', FALSE)) ? $dbpass : $db_password;
$dbPort ="3306";
$db_url = ($dburl = drush_get_option('db-url', FALSE)) ? $dburl : 'mysql://'.$dbUser.':'.$dbPassword.'@'.$dbHost.':' . $dbPort . '/' . $dbName;
##########################################################



# Install core
##########################################################
// let's jump to our site directory before we do anything else
//drush_op('chdir', $dest);

//install
$status = drush_invoke_process('@self', 'site-install', array('standard'), array(
                                                              'account-mail'=> $adminEmail,
                                                              'account-name' => $AdminUsername,
                                                              'account-pass' => $AdminPassword,
                                                              'site-name' => $siteName,
                                                              'locale' => $siteLocale,
                                                              'db-url' => $db_url,
                                                        )
                              );
drush_print('Site Install - ' . (($status) ? 'SUCCESS' : 'FAILURE'));

//run configuration script separately
//include(_drush_core_eval_shebang_script('../config/drush-scripts/configure-site.php'));

/*
$exec_config_script = 'drush ../config/drush-scripts/configure-site.php';
$exec_config_script .= (drush_get_option('debug', FALSE)) ? ' --debug ' : '';
$exec_config_script .= ' --build-src='.$make_file;
drush_shell_exec($exec_config_script);
*/

//@TODO make it possible to bootstrap from here instead of separate file
//get the alias of the new site
//$cur_dir = getcwd();
//$new_site_alias = drush_sitealias_get_record($cur_dir.'#default');

//atempt to fix the error
//drush_set_context('DRUSH_SELECTED_DRUPAL_ROOT', $cur_dir);

//bootsraping
//$boot_status = drush_bootstrap_max_to_sitealias($new_site_alias, DRUSH_BOOTSTRAP_DRUPAL_FULL);
//drush_print('Bootstrap ' . (($boot_status) ? 'SUCCESS' : 'FAILED'));
//drush_print('Current bootstap phase: ' . drush_get_context('DRUSH_BOOTSTRAP_PHASE'));
//($errors = drush_get_context('DRUSH_BOOTSTRAP_ERRORS')) ? print_r($errors) : drush_print('no errors') ;
