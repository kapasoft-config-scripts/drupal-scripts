#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
require_once '../../config/drush-scripts/includes/drush-script-extensions.inc';

//$test_dir_name = drush_get_option('test-dir-name');
$widget_name = drush_get_option('artifact-name', 'blog');
$widget_type = drush_get_option('artifact-type', 'module');
(DEBUG_ON) ? drush_print('Name: '.$widget_name.'; Type:'.$widget_type) : '' ;
//$artifact_name = 'designssquare_com_' . $widget_type . '_' . $widget_name;
//$ver = drush_get_option('ver', '0.1');
//$plus_package = drush_get_option('plus', 'no');
//$env = drush_get_option('env', 'dev');
//
//if ($plus_package == 'yes') {
//    $package_name = 'designssquare-com-' . $widget_type . '-' . $widget_name . '-plus';
//} else {
//    $package_name = 'designssquare-com-' . $widget_type . '-' . $widget_name;
//}
//
//
//$root_dir = getcwd();
//$config = get_config();
//$dist_dir = $config['dist'];
//
////$dist_dir = '/Users/maxit/Sites/drupal/dist/';
//$min = drush_get_option('min-ver', 'no');

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

//loop through different type of artifacts and perform delete, build
foreach (_all_artifact_types() as $key => $type) {

    (DEBUG_ON) ? _log('***Type: ' . $type) : '';
    switch ($env) {
        case 'dev':
            (DEBUG_ON) ? _log('In DEV start to remove...') : '';
            //removing artifacts based on whats in the package
            remove_make_artifacts($widget_name, $type);

            //link artifacts from working directory based on make file
            (DEBUG_ON) ? drush_print('In DEV start to build...') : '';
            foreach (all_artifacts_from_package($widget_name, $type) as $key => $artifact) {
                        $artifact = get_artifact_by_name($artifact['name']);
                        drush_print('Linking ' . $type . ' - ' . $artifact['dev_name'] . '...' . DEV_DIR . $artifact['dev_dir'] . '/' . $artifact['dev_name']);
                        $source = DEV_DIR . $artifact['dev_dir'] . '/' . $artifact['dev_name'];
                        if (file_exists($source)) {
                            drush_shell_exec('sudo ln -s ' . $source . ' ' . $root_dir . '/sites/all/' . $type . 's/' . $artifact['final_name']);
                            print_r(drush_shell_exec_output());
                        } else {
                            drush_print('ERROR:source - ' . $source . ' does not exist');
                        }
                }
            break;
        case 'stage':
            (DEBUG_ON) ? drush_print('In STAGE start to remove...') : '';
            //removing artifacts based on what declared in make file
            remove_make_artifacts($widget_name, $type);

            //adding new modules from remote github
            (DEBUG_ON) ? drush_print('In STAGE start to build...') : '';
            foreach (all_artifacts_from_package($widget_name, $widget_type) as $key => $artifact) {
//                $artifact = get_artifact_by_name($artifact['widget_name']);
//                $dest_dir = 'sites/all/' . $type . 's/' . $artifact['final_name'];
                build_artifact($artifact, 'sites/all');
//                $git_repo = $artifact['repo'] . $artifact['repo_name'] . '.git';
////                drush_print('building new ' . $type . 's....' . $git_repo);
//                execute_command('git clone -b ' . $artifact['branch'] . ' ' . $git_repo . ' ' . $dest_dir);
            }
            break;
        default:
            //removing artifacts
            (DEBUG_ON) ? drush_print('In TEST start to remove...') : '';
            remove_package_artifacts($dist_package, $type);

            //adding by copying from distribution package
            (DEBUG_ON) ? drush_print('In TEST start to build...') : '';
            drush_print('building ' . $type . 's ...sudo cp -Rv ' . $dist_package . '/' . $type . 's/ ' . $root_dir . '/sites/all/' . $type . 's/');
            drush_shell_exec('sudo cp -R ' . $dist_package . '/' . $type . 's/ ' . $root_dir . '/sites/all/' . $type . 's/');
            print_r(drush_shell_exec_output());
            break;
    }

//            if ($env == 'dev') {
////        $handle = opendir($dist_package . '/' . $type . 's/');
////        while (false !== ($module = readdir($handle))) {
////            if ($module !== '.' && $module !== '..') {
////                remove_cur_artifact($module, $type);
////            }
////        }
////        closedir($handle);
//            } elseif ($env == 'stage') {
////        foreach (all_artifacts_from_package($name, $type) as $key => $artifact_name) {
////            remove_cur_artifact($artifact_name, $type);
////        }
//            }
}
////&& $handle = opendir($dist_package.'/modules/')) {
//    while (false !== ($module = readdir($handle))) {
//        if($module !== '.' && $module !== '..'){
//            remove_cur_artifact($module, 'module');
//        }
//    }
//    closedir($handle);
//}elseif($env == 'stage'){
//
//        foreach(all_themes_from_package($name, $type) as $key => $theme_name ){
//            remove_cur_artifact($theme_name,'theme');
//        }
//
//    foreach(all_widget_modules($name,$type) as $key => $widget_name ){
//        remove_cur_artifact($widget_name, 'module');
//    }
//}

//build new one
//    if ($env == "stage") {
//        foreach (all_widget_modules($name, $type) as $key => $widget_name) {
//            $artifact = get_artifact_by_name($widget_name);
//            $dest_dir = 'sites/all/modules/' . $artifact['final_name'];
//            $git_repo = $artifact['repo'] . $artifact['repo_name'] . '.git';
//            drush_print('building new modules....' . $git_repo);
//            drush_shell_exec('git clone -b ' . $artifact['branch'] . ' ' . $git_repo . ' ' . $dest_dir);
//        }
//    } elseif ($env == 'dev' && $handle = opendir($dist_package . '/modules/')) {
//        while (false !== ($module = readdir($handle))) {
//            if ($module !== '.' && $module !== '..') {
//                $artifact = get_artifact_by_name($module);
//                drush_print('Linking module - ' . $module . '...' . DEV_DIR . $artifact['dev_dir'] . '/' . $artifact['name']);
//                $source = DEV_DIR . $artifact['dev_dir'] . '/' . $module;
//                if (file_exists($source)) {
//                    drush_shell_exec('sudo ln -s ' . $source . ' ' . $root_dir . '/sites/all/modules/' . $artifact['dev_name']);
//                    print_r(drush_shell_exec_output());
//                } else {
//                    drush_print('ERROR:source - ' . $source . ' does not exist');
//                }
//            }
//        }
//        closedir($handle);
//    } else {
//        drush_print('building ' . $type . 's ...sudo cp -Rv ' . $dist_package . '/' . $type . 's/ ' . $root_dir . '/sites/all/' . $type . 's/');
//        drush_shell_exec('sudo cp -R ' . $dist_package . '/modules/ ' . $root_dir . '/sites/all/modules/');
//        print_r(drush_shell_exec_output());
//    }

/******THEMES******/
//    if ($type == 'theme') {
//        //delete existing code
//        if ($env == 'dev' && $handle = opendir($dist_package . '/themes/')) {
//            while (false !== ($theme = readdir($handle))) {
//                if ($theme !== '.' && $theme !== '..') {
//                    remove_cur_artifact($theme, 'theme');
//                }
//            }
//            closedir($handle);
//        } elseif ($env == 'stage') {
//            foreach (all_themes_from_package($name, $type) as $key => $theme_name) {
//                remove_cur_artifact($theme_name, 'theme');
//            }
//        }
//
////building themes
//        if ($env == 'stage') {
//            foreach (all_themes_from_package($name, $type) as $key => $t_name) {
//                $artifact = get_artifact_by_name($t_name);
//                $dest_dir = 'sites/all/themes/' . $artifact['final_name'];
//                $git_repo = $artifact['repo'] . $artifact['repo_name'];
//                drush_print('building new themes....' . $git_repo);
//                drush_shell_exec('git clone ' . $git_repo . ' ' . $dest_dir);
//            }
//        } elseif ($env == 'dev' && $handle = opendir($dist_package . '/themes/')) {
//            while (false !== ($theme = readdir($handle))) {
//                if ($theme !== '.' && $theme !== '..') {
//                    $artifact = get_artifact_by_name($theme);
//                    drush_print('Linking theme - ' . $theme . '...' . DEV_DIR . $artifact['dev_dir'] . '/' . $artifact['name'] . ' to ' . $root_dir . '/sites/all/themes/' . $artifact['final_name']);
//                    $source = DEV_DIR . $artifact['dev_dir'] . '/' . $artifact['name'];
//                    if (file_exists($source)) {
//                        drush_shell_exec('sudo ln -s ' . $source . ' ' . $root_dir . '/sites/all/modules/' . $artifact['dev_name']);
//                        print_r(drush_shell_exec_output());
//                    } else {
//                        drush_print('ERROR:source - ' . $source . ' does not exist');
//                    }
//                }
//            }
//            closedir($handle);
//        } else {
//            drush_print('sudo cp -R ' . $dist_package . '/themes/ ' . $root_dir . '/sites/all/themes/');
//            drush_shell_exec('sudo cp -R ' . $dist_package . '/themes/ ' . $root_dir . '/sites/all/themes/');
//        }
//        print_r(drush_shell_exec_output());
//    }

//download all projects specified in the .make file
$make_file = get_make_file($widget_name, $widget_type);
$build_file_parsed = drupal_parse_info_file($make_file);
$project_modules = _project_modules_from_make($build_file_parsed);

foreach ($project_modules as $key => $module) {
    //download
    drush_print('about do download ' . $module . "...");
    drush_print('module ' . $module . ((drush_invoke_process("@self", "pm-download", array($module)) ? ' WAS ' : ' WAS NOT')) . ' downloaded');

}

//@ToDo read from .make file for all themes for the Widget to loop through
////build theme
//drush_print('build themes ...');
//drush_shell_exec('sudo cp -R '.$dist_package.'/theme/ '.$root_dir.'/sites/all/themes/');
//print_r(drush_shell_exec_output());

