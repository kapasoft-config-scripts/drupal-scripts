#!/usr/bin/env drush
<?php
require_once '../../config/drush-scripts/includes/drush-script-extensions.inc';
require_once '../../config/drush-scripts/includes/actions-lib.inc';

// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name');
$artifact_type = drush_get_option('artifact-type', 'module');
//$env = drush_get_option('env','test');
//constraints
//if(!isset($artifact_name) || empty($artifact_name)){drush_die("Artifact name not specified");}
//$package_name = 'designssquare-com-'.$artifact_type.'-'.$artifact_name;
//$artifact_dir = 'designssquare_com_'.$artifact_name;
//$make_file = '../../config/builds/'.$artifact_type.'-builds/designssquare_com_'.$artifact_name.'_'.$artifact_type.'.make';
$make_file = get_make_file($artifact_name, $artifact_type);

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

$actions = get_actions_from_make($make_file);
$glob_vars = array(); //list of return variables to be reused as specified in make file 'result'
$glob_tags = array();//list of tags

print_r($actions);
$solo_action = drush_get_option('name', false);
$active_tags = drush_get_option('tag', false);
if ($solo_action) {
    _log('SOLO actions '.$solo_action);
    //call solo actions
    $action_names = explode(',',$solo_action);
    foreach($action_names as $key => $action_name){
        (isset($actions[$action_name])) ? _applay_action($actions[$action_name], $glob_vars) : _log('action: '.$action_name.' was not found');
    }
} elseif($active_tags){
    _log('TAG actions: '.$active_tags);
    //build tag hash tree
    _build_tags($actions, $glob_tags);
//    _log('Tag tree built:');
//    print_r($glob_tags);
    $all_tags = explode(',', $active_tags);
    foreach($all_tags as $key => $tag){
        //retrieve all actions for particular tag
        $actions_per_tag = $glob_tags[$tag];
        foreach($actions_per_tag as $key=>$action_name){
            $action = $actions[$action_name];
            _applay_action($action, $glob_vars);
        }
    }
}else {
    _log('ALL actions: '.$active_tags);
//call each action
    foreach ($actions as $key => $action) {
        _applay_action($action, $glob_vars);
    }
}

function _applay_action($action, &$glob_vars){
    $func = $action['name'] . "_action";
    $params = (isset($action['param']) && !empty($action['param'])) ? _replace_var(array_values($action['param']), $glob_vars) : array();
    print_r($params);
    if (function_exists($func)) {
        $return = call_user_func_array($func, $params);
        //keeps global variables for later use
        if ($action['result']) {
            $glob_vars[$action['result']] = $return;
        }
    } else {
        _log("ERROR. Func: " . $func . " does not exist");
    }
}

/*
 * looks up if any of the parameters is actual global variable
 * @param: $params
 *      parameter list
 * @param: $g_var
 *      list of global variables
 *
 * @returns parsed lists of parameters
 */
function _replace_var($params, $g_var){
    $parsed_pars = array();
    foreach($params as $key => $par){
        if($par[0] === '@'){
            $variable_name = substr($par,1);
            $parsed_pars[] = $g_var[$variable_name];
        }else{
            $parsed_pars[] = $par;
        }
    }
    return $parsed_pars;
}

/*
 * builds hash tree from tags
*/
function _build_tags($actions, &$glob_tags){
//    _log("^ctions in buiild_tag");
//    print_r($actions);
    //go through each action and grab tags
    foreach($actions as $action_key => $action){
        $tags = explode(',', $action['tag']);
        //store tags in tree
//        _log('^actions');
//        print_r($action);
//        _log('^tags');
//        print_r($tags);
        foreach($tags as $tag_key => $tag){
//            _log('^tag:'.$tag);
            if(!is_array($glob_tags[$tag])){
                $glob_tags[$tag] = array();
            }

            $tag_index = (empty($tag)) ? 'none' : $tag;
            //store key for later use
            $glob_tags[$tag_index][] = $action_key;
        }
    }
}