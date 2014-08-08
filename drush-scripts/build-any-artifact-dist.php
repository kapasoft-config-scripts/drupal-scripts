#!/usr/bin/env drush
<?php
require_once '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name', 'blog');
$artifact_type = drush_get_option('artifact-type', 'module');
$min = drush_get_option('min', 'none');

$package = get_distribution();
$package_name = $package['name'];
$dest = $package['path'].'/'.$package_name;
if (file_exists($dest)) {
    //clean
    remove_distribution($package);
}

//loop through types of artifacts and build one by one
foreach (_all_artifact_types() as $key => $type) {
    _log('***Type: ' . $type);

    $all_artifacts = all_artifacts_from_package($artifact_name, $type);

    //read all artifacts from make file and loop through building one at the time
    if(isset($all_artifacts) && is_array($all_artifacts) && !empty($all_artifacts)){
        if($min != 'min'){
            foreach ($all_artifacts as $key => $artifact) {
                _log('Cooking...' . $artifact['name']);
                build_artifact($artifact, $dest);
            }
        }else{
            build_artifact($artifact, $dest);
        }
    }else{
        _log('INFO: no artifacts of type '.$type.' are specified');
    }
}

//copy documentation
$artifact = get_artifact_by_name($artifact_name);
$src = $dest.'/'.$artifact_type.'s/'.$artifact['final_name'];
_copy_documentation($src, $dest);

//remove git files "sudo rm -rf .git"
(DEBUG_ON) ? _log('removing git repo info ....sudo rm -rf ' . $dest . '/*/*/.git ') : '';
execute_command('sudo rm -rf ' . $dest . '/*/*/.git');
execute_command('sudo rm -rf ' . $dest . '/*/*/.gitignore');

//create compressed file
(DEBUG_ON) ? _log('compresssing package....') : '';
drush_op('chdir', $package['path']);
execute_command('tar -czf ' . $package_name . '.tar.gz '.$package_name);
//execute_command('tar -czf ' . $package_name . '.tar.gz -C '.$package['path'].' '.$dest);

//create zip file
drush_print('ziping package....');
drush_op('chdir', $package['path']);
//to avoid full path, we go to distribution. this can be improved
execute_command('zip -r ' . $package_name . '.zip ' .$package['path']);

drush_print('Done building ' . $package_name);