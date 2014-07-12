#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

//$test_dir_name = drush_get_option('test-dir-name');
$name = drush_get_option('artifact-name','blog');
$type = drush_get_option('artifact-type','module');
$artifact_name = 'designssquare_com_'.$type.'_'.$name;
$ver = drush_get_option('ver','0.1');
$plus_package = drush_get_option('plus', 'no');
$env = drush_get_option('env', 'dev');

if($plus_package == 'yes'){
    $package_name = 'designssquare-com-'.$type.'-'.$name.'-plus';
}else{
    $package_name = 'designssquare-com-'.$type.'-'.$name;
}


$test_artifact_dir = getcwd();
$dist_dir = '/Users/maxit/Sites/drupal/dist/';
$min = drush_get_option('min-ver','no');

if($min == 'yes'){
    $dist_package = $dist_dir.$type.'s/'.$artifact_name.'/'.$package_name.'-'.$ver.'-min';
}else{
    $dist_package = $dist_dir.$type.'s/'.$artifact_name.'/'.$package_name.'-'.$ver;
}

/****MODULE****/
//@ToDo read from .make file for all modules for the Widget to loop through
//building modules
drush_print('building '.$type.'s ...sudo cp -Rv '.$dist_package.'/'.$type.'s/ '.$test_artifact_dir.'/sites/all/'.$type.'s/');

//delete existing code
if ($env == 'dev' && $handle = opendir($dist_package.'/modules/')) {
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
}elseif($env == 'stage'){
    //ToDo
}

drush_print('building new modules....');
if($env == "stage"){
    $dest_dir = 'sites/all/modules/'.get_widget_name($name, 'widget');
    $git_repo = get_repo($name,'widget');
    drush_shell_exec('git clone '.$git_repo.' '.$dest_dir);
    print_r(drush_shell_exec_output());
}else{
    drush_shell_exec('sudo cp -R '.$dist_package.'/modules/ '.$test_artifact_dir.'/sites/all/modules/');
}
print_r(drush_shell_exec_output());

/******THEMES******/
if($type == 'theme'){
    //delete existing code
    if ($env == 'dev' && $handle = opendir($dist_package.'/themes/')) {
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
    }elseif($env == 'stage'){
        //@ToDo
    }


    drush_print('building new themes....');
    if($env == 'stage'){
        $dest_dir = 'sites/all/themes/'.get_widget_name($name, 'theme');
        $git_repo = get_repo($name,'theme');
        drush_shell_exec('git clone '.$git_repo.' '.$dest_dir);
        print_r(drush_shell_exec_output());
    }else{
        drush_shell_exec('sudo cp -R '.$dist_package.'/themes/ '.$test_artifact_dir.'/sites/all/themes/');
    }
    print_r(drush_shell_exec_output());
}

//@ToDo read from .make file for all themes for the Widget to loop through
////build theme
//drush_print('build themes ...');
//drush_shell_exec('sudo cp -R '.$dist_package.'/theme/ '.$test_artifact_dir.'/sites/all/themes/');
//print_r(drush_shell_exec_output());

