#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';


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

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);


/****MODULE****/
//delete existing code
if ($env == 'dev' && $handle = opendir($dist_package.'/modules/')) {
    while (false !== ($module = readdir($handle))) {

        if($module !== '.' && $module !== '..'){
            remove_cur_artifact($module, 'module');
//            drush_print('deleting existing module:'.$module.'....');
//            drush_shell_exec('sudo rm -R '.$test_artifact_dir.'/sites/all/modules/'.$module);
//            print_r(drush_shell_exec_output());
        }
    }
    closedir($handle);
}elseif($env == 'stage'){
    foreach(all_widget_modules($name,$type) as $key => $widget_name ){
        remove_cur_artifact($widget_name, 'module');
    }
}

//build new one
if($env == "stage"){
    foreach(all_widget_modules($name, $type) as $key => $widget_name ){
        $artifact = get_artifact_by_name($widget_name);
        $dest_dir = 'sites/all/modules/'.$artifact['final_name'];
        $git_repo = $artifact['repo'].$artifact['repo_name'].'.git';
        drush_print('building new modules....'.$git_repo);
        drush_shell_exec('git clone -b '.$artifact['branch'].' '.$git_repo.' '.$dest_dir);
    }
}else{
    drush_print('building '.$type.'s ...sudo cp -Rv '.$dist_package.'/'.$type.'s/ '.$test_artifact_dir.'/sites/all/'.$type.'s/');
    drush_shell_exec('sudo cp -R '.$dist_package.'/modules/ '.$test_artifact_dir.'/sites/all/modules/');
}
print_r(drush_shell_exec_output());

/******THEMES******/
if($type == 'theme'){
    //delete existing code
    if ($env == 'dev' && $handle = opendir($dist_package.'/themes/')) {
        while (false !== ($theme = readdir($handle))) {
            if($theme !== '.' && $theme !== '..'){
                  remove_cur_artifact($theme,'theme');
//                drush_print('deleting existing theme:'.$theme.'....');
//                drush_shell_exec('sudo rm -R '.$test_artifact_dir.'/sites/all/themes/'.$theme);
//                print_r(drush_shell_exec_output());
            }
        }
        closedir($handle);
    }elseif($env == 'stage'){
        foreach(all_themes_from_package($name, $type) as $key => $theme_name ){
            remove_cur_artifact($theme_name,'theme');
        }
    }

//building themes
    if($env == 'stage'){
        foreach(all_themes_from_package($name, $type) as $key => $t_name ){
            $artifact = get_artifact_by_name($t_name);
            $dest_dir = 'sites/all/themes/'.$artifact['final_name'];
            $git_repo = $artifact['repo'].$artifact['repo_name'];
            drush_print('building new themes....'.$git_repo);
            drush_shell_exec('git clone '.$git_repo.' '.$dest_dir);
        }
    }else{
        drush_print('sudo cp -R '.$dist_package.'/themes/ '.$test_artifact_dir.'/sites/all/themes/');
        drush_shell_exec('sudo cp -R '.$dist_package.'/themes/ '.$test_artifact_dir.'/sites/all/themes/');
    }
    print_r(drush_shell_exec_output());
}

//@ToDo read from .make file for all themes for the Widget to loop through
////build theme
//drush_print('build themes ...');
//drush_shell_exec('sudo cp -R '.$dist_package.'/theme/ '.$test_artifact_dir.'/sites/all/themes/');
//print_r(drush_shell_exec_output());

