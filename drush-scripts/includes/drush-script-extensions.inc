<?php
date_default_timezone_set('UTC');
$ts = date('Ymdhis');


/******COMMON TASKS SHARED BETWEEN SCRIPTS*******/

//Check to see if we should back things up first.
if (drush_get_option('backup-files')) {
    print_r(args);
    drush_print("BACKING UP FILES...");
    drush_shell_exec('git commit -m "commit before running script on '.$ts.'"');
    $output = drush_shell_exec_output();
    if($output[0] === 'fatal: Not a git repository (or any of the parent directories): .git'){
        //make git repository and do initial commit
        drush_print('Git repository not setup, yet.....initiating one');
        drush_shell_exec('git init');
        drush_shell_exec('git add .');
        drush_shell_exec('git commit -m "initial commit before running script on '.$ts.'"');
    }
//    print_r(drush_shell_exec_output());
    drush_print("File Backup complete.");
}



/**********SCRIPT UTILITY FUNCTIONS**********/
//common variables
$make_file = ($build_src = drush_get_option('build-src', FALSE)) ? $build_src : NULL;
$dest = ($build_dest = drush_get_option('build-dest', FALSE)) ? $build_dest : NULL;

//processing make file
function _process_make_file($make_file, $dest = Null, $options = Null){
    $options = array();
   if($options == Null){
    $options['debug'] = drush_get_option('debug', FALSE);
    $options['no-core'] = drush_get_option('no-core', FALSE);
    $options['yes'] = TRUE;
    ($contr_dest = drush_get_option('contrib-destination', FALSE)) ? $options['contrib-destination'] = $contr_dest : '' ;
   }
//    print_r($options);
//    print_r($make_file);
//    print_r('dest file: '.$dest);
    if($dest){
        drush_print("SITE INSTALL");
        return drush_invoke_process('@none', 'make', array($make_file, $dest), $options);
    }else{
        drush_print("MODULE INSTALL");
        return drush_invoke_process('@self', 'make', array($make_file), $options);
    }

//    return ($dest) ? drush_invoke_process('@none', 'make', array($make_file, $dest), $options) : drush_invoke_process('@self', 'make', array($make_file), $options);
}

/**
 * Make a database backup
 */
function _backup_stuff() {
    // backup the db
    $ts = date('Ymdhis');
    //@ToDo fix drush_invoke_process to work
    //(drush_invoke_process('@self', 'sql-dump', array('result-file' =>  'revert_backup.sql'))) ? drush_print('back up success') : drush_print('no back up') ;
    drush_shell_exec('drush sql-dump --result-file=drush_backup/revert_'.$ts.'_backup.sql --gzip=TRUE');
    print_r(drush_shell_exec_output());
}

function _import_db(){
    drush_shell_exec('drush sqlq --file=drush_backup/db_import.sql');
    print_r(drush_shell_exec_output());
}

function _drop_db(){
    drush_shell_exec('drush sql-drop');
    print_r(drush_shell_exec_output());
}

/*********PHP***********/
//temp solution till we upgrade to PHP5.5 then array_column() is available
function _array_column($input = null, $columnKey = null, $indexKey = null)
{
    // Using func_get_args() in order to check for proper number of
    // parameters and trigger errors exactly as the built-in array_column()
    // does in PHP 5.5.
    $argc = func_num_args();
    $params = func_get_args();

    if ($argc < 2) {
        trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
        return null;
    }

    if (!is_array($params[0])) {
        trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
        return null;
    }

    if (!is_int($params[1])
        && !is_float($params[1])
        && !is_string($params[1])
        && $params[1] !== null
        && !(is_object($params[1]) && method_exists($params[1], '__toString'))
    ) {
        trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
        return false;
    }

    if (isset($params[2])
        && !is_int($params[2])
        && !is_float($params[2])
        && !is_string($params[2])
        && !(is_object($params[2]) && method_exists($params[2], '__toString'))
    ) {
        trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
        return false;
    }

    $paramsInput = $params[0];
    $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

    $paramsIndexKey = null;
    if (isset($params[2])) {
        if (is_float($params[2]) || is_int($params[2])) {
            $paramsIndexKey = (int) $params[2];
        } else {
            $paramsIndexKey = (string) $params[2];
        }
    }

    $resultArray = array();

    foreach ($paramsInput as $row) {

        $key = $value = null;
        $keySet = $valueSet = false;

        if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
            $keySet = true;
            $key = (string) $row[$paramsIndexKey];
        }

        if ($paramsColumnKey === null) {
            $valueSet = true;
            $value = $row;
        } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
            $valueSet = true;
            $value = $row[$paramsColumnKey];
        }

        if ($valueSet) {
            if ($keySet) {
                $resultArray[$key] = $value;
            } else {
                $resultArray[] = $value;
            }
        }

    }

    return $resultArray;
}

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}