#!/usr/bin/env drush
<?php

/*******Available Params*********/
#'build-src' :: path to the make file(default is ../builds/build-drupal-core.make)
#'build-dest' :: name of the dir to install site relative to current dir (default is 'test-core')
#'db-name' :: db name (default 'drupal_test_db')
#'db-user' :: db user (default 'test_user')
#'db-pass' :: db pass (default '38394815')
# Drush options :: debug, no-core, contrib-destination
# Example: sudo drush ../config/drush-scripts/configure-site.php --debug --build-src=../config/builds/build-drupal-core.make
#sudo drush config/drush-scripts/install_site.php --debug --build-dest=test-core --build-src=config/builds/build-drupal-core.make

require_once  '../config/drush-scripts/includes/drush-script-extensions.inc';

#Project Configurations
################
####CALYPSO
//$project = 'test-calypso';
//$db_name = 'test_calypso_db';
//$db_user = 'makapacs';
//$db_password = '38394815';

//$project = 'stage-calypso';
//$db_name = 'stage_calypso_db';
//$db_user = 'stage_calypso';
//$db_password = '38394815';

####CASCADE
//$project = 'test-cascade';
//$db_name = 'test_cascade_db';
//$db_user = 'test_cascade';
//$db_password = '38394815';


//$project = 'cascade';
//$db_name = 'stage_cascade_db';
//$db_user = 'stage_cascade';
//$db_password = '38394815';

####BAZAR
//$project = 'bazar';
//$db_name = 'bazar_dev_db';
//$db_user = 'bazar_dev_user';
//$db_password = '38394815';

//$project = 'test_bazar';
//$db_name = 'test_bazar_db';
//$db_user = 'test_bazar';
//$db_password = '38394815';
//$debug_mode = TRUE;

###MELON
//$project = 'dev_melon';
//$db_name = 'dev_melon_db';
//$db_user = 'dev_melon';
//$db_password = '38394815';
//$debug_mode = TRUE;

###DESIGNS SQUARE
//$project = 'dev_designssquare';
//$db_name = 'dev_designssquare_db';
//$db_user = 'dev_ds_user';
//$db_password = '38394815';
//$debug_mode = TRUE;

###BUILDER
//$env = 'dev_p';
//$env = 'dev';
//$env = 'test';
//$project = $env.'_builder';
//$db_name = $env.'_builder_db';
//$db_user = $env.'_builder';
//$db_password = '38394815';
//$debug_mode = TRUE;

###LOAM
//$env = 'dev_p';
//$env = 'test';
$env = 'stage';
$project = $env.'_loam';
$db_name = $env.'_loam_db';
$db_user = $env.'_loam';
$db_password = '38394815';
$debug_mode = TRUE;

//$project = 'prod_loam';
//$db_name = 'prod_loam_db';
//$db_user = 'prod_loam';
//$db_password = 'loam2014';
//$debug_mode = TRUE;

###SLIDER
//$project = 'dev_slider';
//$db_name = 'dev_slider_db';
//$db_user = 'dev_slider';
//$db_password = '38394815';
//$debug_mode = TRUE;

//$env = 'test';
//$env = 'test';
//$project = $env.'_slider';
//$db_name = $env.'_slider_db';
//$db_user = $env.'_slider';
//$db_password = '38394815';
//$debug_mode = TRUE;

#PORTFOLIO
//$env = 'dev';
//$env = 'test';
//$project = $env.'_portfolio';
//$db_name = $env.'_portfolio_db';
//$db_user = $env.'_portfolio';
//$db_password = '38394815';
//$debug_mode = TRUE;


#SERVICE
//$env = 'dev';
//$env = 'test';
//$project = $env.'_service';
//$db_name = $env.'_service_db';
//$db_user = $env.'_service';
//$db_password = '38394815';
//$debug_mode = TRUE;

###BLOG
//$project = 'dev_blog';
//$db_name = 'dev_blog_db';
//$db_user = 'dev_blog';
//$db_password = '38394815';
//$debug_mode = TRUE;

//$project = 'test_blog';
//$db_name = 'test_blog_db';
//$db_user = 'test_blog';
//$db_password = '38394815';
//$debug_mode = TRUE;

//###Gallery
//$env = 'dev';
//$project = $env.'_gallery';
//$db_name = $env.'_gallery_db';
//$db_user = $env.'_gallery';
//$db_password = '38394815';
//$debug_mode = TRUE;

###Newsletter
//$env = 'dev';
//$project = $env.'_newsletter';
//$db_name = $env.'_newsletter_db';
//$db_user = $env.'_newsletter';
//$db_password = '38394815';
//$debug_mode = TRUE;

###Podcast
//$env = 'dev';
//$project = $env.'_podcast';
//$db_name = $env.'_podcast_db';
//$db_user = $env.'_podcast';
//$db_password = '38394815';
//$debug_mode = TRUE;

###Espresso
//$project = 'dev_espresso';
//$db_name = 'dev_espresso_db';
//$db_user = 'dev_espresso';
//$db_password = '38394815';
//$debug_mode = TRUE;

//$env = 'test';
////$env = 'dev_p';
////$env = 'test_p';
//$project = $env.'_espresso';
//$db_name = $env.'_espresso_db';
//$db_user = $env.'_espresso';
//$db_password = '38394815';
//$debug_mode = TRUE;

###CHURCH
//$env = 'stage';
//$env = 'test';
//$env = 'dev';
//$project = $env.'_church';
//$db_name = $env.'_church_db';
//$db_user = $env.'_church';
//$db_password = '38394815';
//$debug_mode = TRUE;

###Sample_Data
//$env = 'test';
//$env = 'test';
//$env = 'dev';
//$project = $env.'_sam_data';
//$db_name = $env.'_sam_data_db';
//$db_user = $env.'_sam_data';
//$db_password = 'password';
//$debug_mode = TRUE;

//###CKEDitor
//$env = 'dev';
//$project = $env.'_ckeditor';
//$db_name = $env.'_ckeditor_db';
//$db_user = $env.'_ckeditor';
//$db_password = '38394815';
//$debug_mode = TRUE;

# Site Configurations
##########################################################
$AdminUsername="admin";
$AdminPassword="password";
$adminEmail="admin@example.com";
$siteName= $project." Site";

$siteSlogan="staging ". $project ." theme";
//$siteSlogan="testing ". $project ." theme";
$siteLocale="en";
##########################################################

#Download
#########################################################
$default_dest = $project;
$default_make = '../builds/build-drupal-core.make';
$make_file = ($build_src = drush_get_option('build-src', FALSE)) ? $build_src : $default_make;
($debug_mode) ? drush_print('DEBUG: Make File: '.$make_file) : '';
$dest = ($build_dest = drush_get_option('build-dest', FALSE)) ? $build_dest : $default_dest;
($debug_mode) ? drush_print('DEBUG: Dest folder: ' . $dest) : '';
$status = _process_make_file($make_file, $dest);
//$status = drush_invoke_process('@none', 'make', array($make_file, $dest));
drush_print('Make Download '.(($status) ? "SUCCESS" : "FAILURE" ));

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
drush_op('chdir', $dest);

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
