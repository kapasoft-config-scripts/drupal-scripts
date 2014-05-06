#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

//$test_dir_name = drush_get_option('test-dir-name');
$name = drush_get_option('artifact-name','blog');
$type = drush_get_option('artifact-type','module');
$artifact_name = 'designssquare_com_'.$type.'_'.$name;
$ver = drush_get_option('ver','0.1');
$package_name = 'designssquare-com-'.$type.'-'.$name;
$test_artifact_dir = getcwd();
$dist_dir = '/Users/maxit/Sites/drupal/dist/';
$dist_package = $dist_dir.$type.'s/'.$artifact_name.'/'.$package_name.'-'.$ver;

/****MODULE****/
//@ToDo read from .make file for all modules for the Widget to loop through
//building modules
drush_print('building '.$type.'s ...sudo cp -Rv '.$dist_package.'/'.$type.'s/ '.$test_artifact_dir.'/sites/all/'.$type.'s/');

//delete existing code
if ($handle = opendir($dist_package.'/modules/')) {
    while (false !== ($module = readdir($handle))) {

        if($module !== '.' && $module !== '..'){
            drush_print('deleting existing module:'.$module.'....');
            drush_shell_exec('sudo rm -R '.$test_artifact_dir.'/sites/all/modules/'.$module);
            print_r(drush_shell_exec_output());
        }
        // do something with the file
        // note that '.' and '..' is returned even
    }
    closedir($handle);
}

drush_print('building new modules....');
drush_shell_exec('sudo cp -R '.$dist_package.'/modules/ '.$test_artifact_dir.'/sites/all/modules/');
print_r(drush_shell_exec_output());

/******THEMES******/
if($type == 'theme'){
    //delete existing code
    if ($handle = opendir($dist_package.'/themes/')) {
        while (false !== ($theme = readdir($handle))) {
            if($theme !== '.' && $theme !== '..'){
                drush_print('deleting existing theme:'.$theme.'....');
                drush_shell_exec('sudo rm -R '.$test_artifact_dir.'/sites/all/themes/'.$theme);
                print_r(drush_shell_exec_output());
            }
            // do something with the file
            // note that '.' and '..' is returned even
        }
        closedir($handle);
    }

    drush_print('building new themes....');
    drush_shell_exec('sudo cp -R '.$dist_package.'/themes/ '.$test_artifact_dir.'/sites/all/themes/');
    print_r(drush_shell_exec_output());
}

//@ToDo read from .make file for all themes for the Widget to loop through
////build theme
//drush_print('build themes ...');
//drush_shell_exec('sudo cp -R '.$dist_package.'/theme/ '.$test_artifact_dir.'/sites/all/themes/');
//print_r(drush_shell_exec_output());

