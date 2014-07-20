<?php
date_default_timezone_set('UTC');
$ts = date('Ymdhis');


/******COMMON TASKS SHARED BETWEEN SCRIPTS*******/

//Check to see if we should back things up first.
if (drush_get_option('backup-files')) {
    print_r(args);
    drush_print("BACKING UP FILES...");
    drush_shell_exec('git commit -m "commit before running script on ' . $ts . '"');
    $output = drush_shell_exec_output();
    if ($output[0] === 'fatal: Not a git repository (or any of the parent directories): .git') {
        //make git repository and do initial commit
        drush_print('Git repository not setup, yet.....initiating one');
        drush_shell_exec('git init');
        drush_shell_exec('git add .');
        drush_shell_exec('git commit -m "initial commit before running script on ' . $ts . '"');
    }
//    print_r(drush_shell_exec_output());
    drush_print("File Backup complete.");
}


/**********SCRIPT UTILITY FUNCTIONS**********/
//common variables
$make_file = ($build_src = drush_get_option('build-src', FALSE)) ? $build_src : NULL;
$dest = ($build_dest = drush_get_option('build-dest', FALSE)) ? $build_dest : NULL;

//processing make file
function _process_make_file($make_file, $dest = Null, $options = Null)
{
    $options = array();
    if ($options == Null) {
        $options['debug'] = drush_get_option('debug', FALSE);
        $options['no-core'] = drush_get_option('no-core', FALSE);
        $options['yes'] = TRUE;
        ($contr_dest = drush_get_option('contrib-destination', FALSE)) ? $options['contrib-destination'] = $contr_dest : '';
    }
    print("Debug - OPTIONS:");
    print_r($options);
//    print_r($make_file);
//    print_r('dest file: '.$dest);
    if ($dest) {
        drush_print("SITE INSTALL");
        return drush_invoke_process('@none', 'make', array($make_file, $dest), $options);
    } else {
        drush_print("MODULE INSTALL");
        return drush_invoke_process('@self', 'make', array($make_file), $options);
    }

//    return ($dest) ? drush_invoke_process('@none', 'make', array($make_file, $dest), $options) : drush_invoke_process('@self', 'make', array($make_file), $options);
}

/**
 * Make a database backup
 */
function _backup_stuff()
{

    $dest = 'drush_backup';
    if (!file_exists($dest)) {
        drush_print("path to dest folder " . $dest . " does not exist. Creating...");
        drush_shell_exec('mkdir ' . $dest);
        drush_shell_exec('chrom -R 777 ' . $dest);
    }

    // backup the db
    $ts = date('Ymdhis');
    //@ToDo fix drush_invoke_process to work
    //(drush_invoke_process('@self', 'sql-dump', array('result-file' =>  'revert_backup.sql'))) ? drush_print('back up success') : drush_print('no back up') ;
    drush_shell_exec('drush sql-dump --result-file=' . $dest . '/revert_' . $ts . '_backup.sql --gzip=TRUE');
    print_r(drush_shell_exec_output());
}

function _import_db()
{
//    drush_shell_exec('drush sqlq --file=drush_backup/db_import.sql');
    drush_shell_exec('drush sql-cli < drush_backup/db_import.sql');
    print_r(drush_shell_exec_output());
}

function _drop_db()
{
    drush_shell_exec('drush sql-drop --yes');
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
    $paramsColumnKey = ($params[1] !== null) ? (string)$params[1] : null;

    $paramsIndexKey = null;
    if (isset($params[2])) {
        if (is_float($params[2]) || is_int($params[2])) {
            $paramsIndexKey = (int)$params[2];
        } else {
            $paramsIndexKey = (string)$params[2];
        }
    }

    $resultArray = array();

    foreach ($paramsInput as $row) {

        $key = $value = null;
        $keySet = $valueSet = false;

        if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
            $keySet = true;
            $key = (string)$row[$paramsIndexKey];
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
    $args[] = & $data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

/***********Stage ***********/
/*
 * param: $dest - the location for custom modules without traling slash
 */
function stage_modules($artifact_type, $artifact_name, $dest = NULL, $debug = TRUE)
{

    $git_util_repo = 'https://github.com/kapasoft-config-scripts/';
    $git_module_repo = 'https://github.com/kapasoft-drupal-modules/';
    $module_dest_dir = (isset($dest)) ? $dest . '/' : 'sites/all/modules/';

    //retrieving modules to be included in the build
    $make_file = '../../config/builds/' . $artifact_type . '-builds/designssquare_com_' . $artifact_name . '_' . $artifact_type . '.make';
    $build_file_parsed = drupal_parse_info_file($make_file);
    $widget_modules = _array_column(array_orderby($build_file_parsed['widget'], 'order', SORT_ASC), "name");

    /*****DEBUG******/
    if ($debug) {
        drush_print('make file: ' . $make_file);
        drush_print('Modules to include:');
        print_r($widget_modules);
    }

    foreach ($widget_modules as $key => $module) {
        $widget_name = null;
        switch ($module) {
            case 'designssquare_lib':
                $repo_name = 'designssquare-utils.git';
                $source_repo = $git_util_repo . $repo_name;
                $widget_name = 'designssquare_com_lib';
                break;
            case  'designssquare_alias_path':
                $repo_name = 'designssquare_alias_path.git';
                $source_repo = $git_util_repo . $repo_name;
                $widget_name = 'designssquare_lib_alias';
                break;
            case  'designssquare_lib_assets':
                $repo_name = 'designssquare-lib-assets.git';
                $source_repo = $git_util_repo . $repo_name;
                $widget_name = 'designssquare_lib_assets';
                break;
            default:
                if ($artifact_type == 'theme') {
                    //installing theme's module
                    $repo_name = $artifact_name . '.git';
                    $source_repo = $git_module_repo . $repo_name;
                    $widget_name = 'designssquare_com_module_' . $artifact_name;
                }
                break;
        }

        if (isset($widget_name)) {
            if (is_dir($module_dest_dir . $widget_name)) {
                drush_print('Removing module ' . $widget_name . ' before staging...');
                drush_shell_exec('sudo rm -R ' . $module_dest_dir . $widget_name);
                print_r(drush_shell_exec_output());
            }
            drush_print('staging ' . $widget_name . ' module ...' . $source_repo);
            drush_shell_exec('git clone ' . $source_repo . ' ' . $module_dest_dir . $widget_name);
            print_r(drush_shell_exec_output());
        }
    }
}

/************Building Dist***********/

function build_modules($artifact_type, $artifact_name, $dest, $debug = TRUE)
{
    $git_util_repo = 'https://github.com/kapasoft-config-scripts/';
    $git_module_repo = 'https://github.com/kapasoft-drupal-modules/';
    //retrieving modules to be included in the build
    $make_file = get_make_file($artifact_name, $artifact_type, 'dev');
    drush_print('****make-file: ' . $make_file);
    $build_file_parsed = drupal_parse_info_file($make_file);
    $widget_modules = _array_column(array_orderby($build_file_parsed['widget'], 'order', SORT_ASC), "name");

    /*****DEBUG******/
    if ($debug) {
        drush_print('Modules to include:');
        print_r($widget_modules);
    }

    foreach ($widget_modules as $key => $module) {

        $artifact_ins = get_artifact_by_name($module);
        $widget_dependency_name = $artifact_ins['name'];
        $widget_dist_dest_lib = $dest . '/modules/' . $artifact_ins['final_name'];
        drush_print('building ' . $widget_dependency_name . ' module ...' . $artifact_ins['repo_name']);
        drush_shell_exec('git clone -b ' . $artifact_ins['branch'] . ' ' . $artifact_ins['repo'] . $artifact_ins['repo_name'] . '.git ' . $widget_dist_dest_lib);
        print_r(drush_shell_exec_output());

    }
}

function _copy_documentation($src, $dest)
{
    drush_print('copying documentation ....' . $dest . '/docs/* from ' . $src . '/docs/*');
    drush_shell_exec('sudo mkdir ' . $dest . '/docs/');
    print_r(drush_shell_exec_output());
    drush_shell_exec('sudo sudo cp -R ' . $src . '/docs/* ' . $dest . '/docs/');
    print_r(drush_shell_exec_output());
}

/**********Configure*********/

function configure_ckeditor($module_name = 'designssquare_com_ckeditor')
{
//    $artifact_dir = drupal_get_path('module', $module_name);
//    $artifact_dir_name = array_pop(explode( "/", $artifact_dir));
    $artifact = get_artifact_by_name($module_name);
//    drush_print("dir_name:".$artifact_dir_name);

//Link CKeditor
    drush_print('Linking CKeditor library....');
    $editor_dest = 'sites/all/libraries/ckeditor';
    if (!file_exists('sites/all/libraries')) {
        drush_print('sites/all/libraries directory does not exist...creating one');
        drush_shell_exec('sudo mkdir sites/all/libraries');
        print_r(drush_shell_exec_output());
    }

    if (file_exists('sites/all/libraries/ckeditor')) {
        drush_print('sites/all/libraries/ckeditor directory already exist...removing');
        drush_shell_exec('sudo rm -R sites/all/libraries/ckeditor');
        print_r(drush_shell_exec_output());
    }
    $editor_src = '../modules/' . $artifact['final_name'] . '/libraries/ckeditor';
//$editor_src = $artifact_dir.'/libraries/ckeditor';
    drush_print('linking ckeditor ' . $editor_dest . ' to path ' . $editor_src);
    (symlink($editor_src, $editor_dest)) ? 'linking widget named ' . $editor_dest . ' to path ' . $editor_src : "***ERROR: didn't limked to" . $editor_src;


    //@ToDo set profiles for IMCE to work
    $imce_roles_profiles = array(
        4 =>
        array(
            'weight' => '0',
            'public_pid' => '1',
        ),
        3 =>
        array(
            'weight' => '0',
            'public_pid' => '1',
        ),
        2 =>
        array(
            'weight' => 11,
            'public_pid' => '1',
        ),
        1 =>
        array(
            'weight' => 12,
            'public_pid' => 0,
        ),
    );
    variable_set('imce_roles_profiles', $imce_roles_profiles);


}


function configure_audio($module_name)
{
//    $artifact_dir = drupal_get_path('module', $module_name);
//    $artifact_dir_name = array_pop(explode( "/", $artifact_dir));
    $artifact = get_artifact_by_name($module_name);
    //configure audio
    variable_set('audiofield_audioplayer', "wpaudioplayer");
    $payer_audio_src = "sites/all/modules/" . $artifact['final_name'] . "/libraries/player";
    if (!file_exists($payer_audio_src)) {
        drush_die("path to audo player lib does not exist: " . $payer_audio_src, 0);
    }
    variable_set('audiofield_players_dir', $payer_audio_src);
    drush_print('...configured audio player lib to path ' . $payer_audio_src);

}

/****
*
 * @param: --mail
 * email account needs to be specified
 *
 * @param: --password
 * the passowrd of the new user
 *
 * @param --role
 * the role of the new user
 *
 * @param(direct): user_name
 * the name of user is added
 *
 *
 *
 *
 *****/
function add_user($user_name){
//    $email = variable_get('mail', 'admin@domain.com');
//    $password = variable_get('password', 'password1');
    $role = drush_get_option('role', 'anonymous user');

    drush_user_create($user_name);
    drush_user_add_role($role, $user_name);
}

//retrieve the name of widget
function get_widget_name($name, $type)
{
    $artifact = get_artifact_by_name($name);
    if (!empty($artifact['name'])) {
        $artifact_name = $artifact['final_name'];
    } else {
        $artifact_name = 'designssquare_com_' . $type . '_' . $name;
    }
    return $artifact_name;
}

function get_repo($name, $type)
{
    $repo_name = '';
    $repo_list = get_repos();
    switch ($type) {
        case 'widget':
        case 'module':
            $repo_name = $repo_list['module'] . $name . '.git';
            break;
        case 'theme':
            $repo_name = $repo_list['theme'] . $name . '.git';
            break;
        case 'site':
            $repo_name = $repo_list['site'] . $name . '.git';
            break;
        default:
            $repo_name = $repo_list['utils'] . $name . '.git';
            break;
    }
    return $repo_name;
}

function all_themes_from_package($name, $type)
{
    //add the main theme
    $themes = array();

    //add dependent themes
    //read make file
    //retrieving modules to be included in the build
    $make_file = get_make_file($name, $type);
//        drush_print('****make-file: '.$make_file);
    if (!file_exists($make_file)) {
        drush_die('make file - ' . $make_file . ' does not exist');
    }
    $build_file_parsed = drupal_parse_info_file($make_file);
//        drush_print('***parsed_make: ');
//        print_r($build_file_parsed);
    $themes_configured = _array_column(array_orderby($build_file_parsed['theme'], 'order', SORT_ASC), "name");

    //clean out dependencies part of the widget and doesn't need to be build
    foreach ($themes_configured as $key => $m_name) {
        $art = get_artifact_by_name($m_name);
        if (!empty($art['name'])) {
            $themes[] = $m_name;
        }
    }


    return $themes;
}

function all_widget_modules($name, $type)
{
    $min = drush_get_option('min-ver', 'no');
    $is_min = ($min == 'yes') ? true : false;
    $widget_modules = array();

    if (!$is_min) {
        //add dependencies

        //read make file
        //retrieving modules to be included in the build
        $make_file = get_make_file($name, $type);
        if (!file_exists($make_file)) {
            drush_die('make file - ' . $make_file . ' does not exist');
        }
        $build_file_parsed = drupal_parse_info_file($make_file);
        $widget_modules_configured = _array_column(array_orderby($build_file_parsed['widget'], 'order', SORT_ASC), "name");

        //clean out dependencies part of the widget and doesn't need to be build
        foreach ($widget_modules_configured as $key => $m_name) {
            $art = get_artifact_by_name($m_name);
            if (!empty($art['name'])) {
                $widget_modules[] = $m_name;
            }
        }
    } else {
        //add the main module
        $widget_modules[] = $name;
    }

    drush_print('Widget Modules:');
    print_r($widget_modules);
    return $widget_modules;
}

function get_make_file($name, $type)
{
    $plus_package_option = drush_get_option('plus', FALSE);
    $plus_package = ($plus_package_option && $plus_package_option == 'yes') ? 'yes' : 'no';
    $config = get_config();
    if ($plus_package == 'no') {
        $make_file = $config['root'] . '/builds/' . $type . '-builds/designssquare_com_' . $name . '_' . $type . '.make';
    } else {
        $make_file = $config['root'] . '/builds/' . $type . '-builds/designssquare_com_' . $name . '_' . $type . '_plus' . '.make';
    }

    drush_print('****make_file:' . $make_file);
    return $make_file;
}

function get_config()
{
    $config = array();
    //@ToDo getenv() not working
//    $config['root'] = getenv('WIDGET_BUILD_ROOT');
    $config['root'] = '/Users/maxit/Sites/drupal/config';
//    $var = drush_shell_exec('echo $WIDGET_BUILD_ROOT');
//    print_r(drush_shell_exec_output());
//    print_r(getenv('SERVER_NAME'));
//    drush_print('***path: '.$var);
//    drush_print('WIDGET_BUILD_ROOT:'.$config['root']);
    return $config;
}

function remove_cur_artifact($name, $type)
{
    $root = getcwd();
    $artifact = get_artifact_by_name($name);
    $dest_dir = $root . '/sites/all/' . $type . 's/' . $artifact['final_name'];
    if (file_exists($dest_dir)) {
        drush_print('deleting existing ' . $type . ': ' . $name . '....');
        drush_shell_exec('sudo rm -R ' . $dest_dir);
        print_r(drush_shell_exec_output());
    }
}

function get_artifact_by_name($name)
{
    $list_of_repos = get_repos();
    $artifact = array();
    $artifact['name'] = '';
    $artifact['repo'] = '';
    $artifact['repo_name'] = '';
    $artifact['final_name'] = '';
    $artifact['branch'] = 'master';

    switch ($name) {
        case 'designssquare_lib':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['utils'];
            $artifact['repo_name'] = 'designssquare-utils';
            $artifact['final_name'] = 'designssquare_com_lib';
            break;
        case  'designssquare_alias_path':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['utils'];
            $artifact['repo_name'] = 'designssquare_alias_path';
            $artifact['final_name'] = 'designssquare_com_lib_alias';
            break;
        case  'designssquare_lib_assets':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['utils'];
            $artifact['repo_name'] = 'designssquare-lib-assets';
            $artifact['final_name'] = 'designssquare_com_lib_assets';
            break;
        case  'designssquare_lib_feature_assets':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['utils'];
            $artifact['repo_name'] = 'designssquare-lib-feature-assets';
            $artifact['final_name'] = 'designssquare_com_lib_feature_assets';
            break;
        case  'designssquare_lib_sample_data':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['utils'];
            $artifact['repo_name'] = 'designssquare-lib-sample-data';
            $artifact['final_name'] = 'designssquare_com_lib_sample_data';
            break;
        case  'designssquare_theme_blocks':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'designssquare_com_theme_blocks';
            $artifact['final_name'] = 'designssquare_com_theme_blocks';
            break;
        case  'designssquare_theme_blocks_plus':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'designssquare_com_theme_blocks';
            $artifact['final_name'] = 'designssquare_com_theme_blocks_plus';
            $artifact['branch'] = 'plus';
            break;
        case  'blog':
        case  'designssquare_com_blog':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'blog';
            $artifact['final_name'] = 'designssquare_com_widget_blog';
            break;
        case 'ckeditor':
        case 'designssquare_com_ckeditor':
        case 'designssquare_utils_ckeditor':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['utils'];
            $artifact['repo_name'] = 'designssquare-utils-ckeditor';
            $artifact['final_name'] = 'designssquare_com_utils_ckeditor';
            break;
        case 'commerce':
        case  'designssquare_com_commerce':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'commerce';
            $artifact['final_name'] = 'designssquare_com_widget_commerce';
            break;
        case 'gallery':
        case  'designssquare_com_widget_gallery':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'gallery';
            $artifact['final_name'] = 'designssquare_com_widget_gallery';
            break;
        case 'slider':
        case  'designssquare_com_slider':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'slider';
            $artifact['final_name'] = 'designssquare_com_widget_slider';
            break;
        case 'newsletter':
        case  'designssquare_com_widget_newsletter':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'newsletter';
            $artifact['final_name'] = 'designssquare_com_widget_newsletter';
            break;
        case 'podcast':
        case  'designssquare_com_widget_podcast':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'podcast';
            $artifact['final_name'] = 'designssquare_com_widget_podcast';
            break;
        case 'portfolio':
        case  'designssquare_com_widget_portfolio':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'portfolio';
            $artifact['final_name'] = 'designssquare_com_widget_portfolio';
            break;
        case  'espresso':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['theme'];
            $artifact['repo_name'] = 'espresso';
            $artifact['final_name'] = 'espresso';
            break;
        case  'espresso_theme':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'espresso';
            $artifact['final_name'] = 'designssquare_com_theme_espresso';
            break;
        case  'espresso_theme_plus':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'espresso';
            $artifact['final_name'] = 'desigssquare_com_theme_espresso_plus';
            $artifact['branch'] = 'plus';
            break;
        case  'builder_theme':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'builder';
            $artifact['final_name'] = 'designssquare_com_theme_builder';
            break;
        case  'builder_theme_plus':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['module'];
            $artifact['repo_name'] = 'builder';
            $artifact['final_name'] = 'desigssquare_com_theme_builder_plus';
            $artifact['branch'] = 'plus';
            break;
        case  'bootstrap':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['theme'];
            $artifact['repo_name'] = 'bootstrap';
            $artifact['final_name'] = 'bootstrap';
            break;
        case 'church':
        case  'designssquare_com_site_church':
            $artifact['name'] = $name;
            $artifact['repo'] = $list_of_repos['site'];
            $artifact['repo_name'] = 'church';
            $artifact['final_name'] = 'designssquare_com_site_church';
            break;
    }

    return $artifact;
}

function get_repos()
{
    $repo_list = array();
    $repo_list['module'] = 'https://github.com/kapasoft-drupal-modules/';
    $repo_list['theme'] = 'https://github.com/kapasoft-drupal-themes/';
    $repo_list['site'] = 'https://github.com/kapasoft-drupal-site-modules/';
    $repo_list['utils'] = 'https://github.com/kapasoft-config-scripts/';
    return $repo_list;
}

//retrieve projects modules from the make file
function _project_modules_from_make($make_file_parsed)
{
    $projects_final = array();
    foreach ($make_file_parsed['projects'] as $key => $project) {
        if ($key != 'drupal') {

            if (is_array($project) && isset($project['version'])) {
                $projects_final[] = $key . '-7.x-' . $project['version'];
            } else {
                $projects_final[] = $make_file_parsed['projects'][$key];
            }
        }
    }
    return array_values($projects_final);
}

