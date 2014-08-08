#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
require_once '../../config/drush-scripts/includes/drush-script-extensions.inc';

//$test_dir_name = drush_get_option('test-dir-name');
$widget_name = drush_get_option('artifact-name', 'blog');
$widget_type = drush_get_option('artifact-type', 'module');
(DEBUG_ON) ? drush_print('Name: ' . $widget_name . '; Type:' . $widget_type) : '';
$env = drush_get_option('env', 'dev');


$config = get_config();
$root_dir = getcwd();
$distribution = get_distribution();

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

    _log('***Type: ' . $type);
    switch ($env) {
        case 'dev':
            _log('In DEV start to remove...');
            //removing artifacts based on whats in the package
            remove_make_artifacts($widget_name, $type);

            //link artifacts from working directory based on make file
            (DEBUG_ON) ? drush_print('In DEV start to build...') : '';
            foreach (all_artifacts_from_package($widget_name, $type) as $key => $item) {
                $artifact = get_artifact_by_name($item['name']);
                _log('Linking ' . $type . ' - ' . $artifact['dev_name'] . '...' . $config['work_dir'] . '/' . $artifact['dev_dir'] . '/' . $artifact['dev_name']);
                $source = $config['work_dir'] . '/' . $artifact['dev_dir'] . '/' . $artifact['dev_name'];
                if (file_exists($source)) {
                    execute_command('sudo ln -s ' . $source . ' ' . $root_dir . '/sites/all/' . $type . 's/' . $artifact['final_name']);
                } else {
                    _log('ERROR:source - ' . $source . ' does not exist');
                }
            }
            break;
        case 'stage':
            _log('In STAGE start to remove...');
            //removing artifacts based on what declared in make file
            remove_make_artifacts($widget_name, $type);

            //adding new modules from remote github
            (DEBUG_ON) ? drush_print('In STAGE start to build...'.$type) : '';
            foreach (all_artifacts_from_package($widget_name, $type) as $key => $artifact) {
//                $artifact = get_artifact_by_name($widget_name);
                build_artifact($artifact, 'sites/all');
//                $dest_dir = 'sites/all/' . $type . 's/' . $artifact['final_name'];
//                $git_repo = $artifact['repo'] . $artifact['repo_name'] . '.git';
//                _log('building new ' . $type . 's....' . $git_repo);
//                drush_shell_exec('git clone -b ' . $artifact['branch'] . ' ' . $git_repo . ' ' . $dest_dir);
            }
            break;
        default:
            $dist_full_path = $distribution['path'] . '/' . $distribution['name'];
            $source_dir = $dist_full_path . '/' . $type . 's';
            if (file_exists($source_dir)) {
                //removing artifacts
                _log('In TEST start to remove...');
                remove_package_artifacts($dist_full_path, $type);

                //adding by copying from distribution package
                _log('In TEST start to build...');

                execute_command('sudo cp -R ' . $source_dir . '/ ' . $root_dir . '/sites/all/' . $type . 's/');
            }else{
                _log('INFO: There are not artifacts of type: '.$type.' to build');
            }
            break;
    }
}

//download all projects specified in the .make file
$make_file = get_make_file($widget_name, $widget_type);
$build_file_parsed = drupal_parse_info_file($make_file);
$project_modules = _project_modules_from_make($build_file_parsed);

foreach ($project_modules as $key => $module) {
    //download
    _log('about do download ' . $module . "...");
    _log('module ' . $module . ((drush_invoke_process("@self", "pm-download", array($module)) ? ' WAS ' : ' WAS NOT')) . ' downloaded');
}

//@ToDo read from .make file for all themes for the Widget to loop through
////build theme
//drush_print('build themes ...');
//drush_shell_exec('sudo cp -R '.$dist_package.'/theme/ '.$root_dir.'/sites/all/themes/');
//print_r(drush_shell_exec_output());

