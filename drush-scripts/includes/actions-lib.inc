<?php
/**
 * Actions Declared
 */

/*Configure Video action configures video by using video library located at MODULE_DIR/libraries/ dir

 * @param: $module_name
 *      name of the module containing vido lib. It is also assumed that the lib is located PATH_TO_MODULE/libraries/player dir
 * @param: $video_player
 *      the name of audio player(i.e. wpaudioplayer). By default, the player is wpaudioplayer
 *
 * [SAMPLE]
 * action[configure_audio][name]=configure_video
 * action[configure_audio][order]=3
 * action[configure_audio][param][module]=MODULE-NAME
 */

function configure_video_action($module_name, $video_player = 'video-js' ){
  //enabling module
    _log('Enabling module - videojs....');
    enable_module_action('videojs');

  //Link VideoJs
    _log('Linking videojs....');
    $current_dir = getcwd();
    $player_dest = $current_dir.'/sites/all/libraries/'.$video_player;
    if(!file_exists('sites/all/libraries')){
        _log('sites/all/libraries directory does not exist...creating one');
        execute_command('sudo mkdir sites/all/libraries');
    }

    $path = drupal_get_path('module',$module_name);
    $payer_src = $current_dir.'/'.$path.'/libraries/video-js';
    _log('linking player '.$player_dest.' to path '.$payer_src);
    link_action($payer_src, $player_dest);
}

/*
* configures editor by default ckeditor. It also uses Imce module for uploading/inserting images
 * @ToDo currently only supports CKEditor but like to have others
 * @param: $module_name
 *      name of the module containing editor library
 *
 * @param: $editor
 *      type of the editor to configure by default ckeditor
 */

function configure_editor_action($module_name, $editor = 'ckeditor' ){
    _log('***ConfigEditor. mod_name: '.$module_name);
    $current_dir = getcwd();
    $editor_dest = $current_dir.'/sites/all/libraries/ckeditor';
    $path = drupal_get_path('module',$module_name);

    //enabling module
    _log('Enabling module - '.$editor.'....');
    enable_module_action($editor);

    //enabling module
    _log('Enabling module - imce....');
    enable_module_action('imce');

    //link CKeditor
    _log('Linking '.$editor.' library....');

    if(!file_exists('sites/all/libraries')){
        _log('sites/all/libraries directory does not exist...creating one');
        execute_command('sudo mkdir sites/all/libraries');
    }

    if(file_exists($editor_dest)){
        _log("destination - ".$editor_dest." already exist....deleting");
        execute_command('sudo rm -R '.$editor_dest);
    }

    $editor_src = $current_dir.'/'.$path.'/libraries/ckeditor';
//    if(!file_exists($editor_src)){
//        _log("ERROR: source - ".$editor_src." does not exist...");
//    }

//    drush_print('linking ckeditor '.$editor_dest.' to path '.$editor_src);
    link_action($editor_src, $editor_dest);
}



/*Configure Audio action configures audio player by using player library located at MODULE_DIR/libraries/player
 * @param: name of the module containing audio lib. It also assumed that the lib is located PATH_TO_MODULE/libraries/player dir
 * @param: the name of audio player(i.e. wpaudioplayer). By default, the player is wpaudioplayer
 *
 * @param: $module_name
 *      name of the module
 *
 * @param: $audio_player
 *      name for the audio player to use. By default, the player name is 'wpaudioplayer'
 *
 * [SAMPLE]
 * action[configure_audio][name]=configure_audio
 * action[configure_audio][order]=3
 * action[configure_audio][param][module]=MODULE-NAME
 */

function configure_audio_action($module_name, $audio_player = "wpaudioplayer")
{

    $path = drupal_get_path('module',$module_name);
    //configure audio
    set_action('audiofield_audioplayer', $audio_player);
    $payer_audio_src = $path . "/libraries/player";
    if (!file_exists($payer_audio_src)) {
        drush_die("path to audio player lib does not exist: " . $payer_audio_src, 0);
    }
    variable_set('audiofield_players_dir', $payer_audio_src);
    _log('...configured audio player lib to path ' . $payer_audio_src);
}


/*
 * Gets the make file for widget of type(i.e.'module','theme')
 */
function get_widget_make_action(){
    $artifact_name = drush_get_option('artifact-name', 'blog');
    $artifact_type = drush_get_option('artifact-type','module');

    $make_file = get_make_file($artifact_name, $artifact_type);

    return $make_file;
}

/*
 * Configure_dependencies reads all the 'widget' modules and 'project' modules from make file. Then downloads all missing dependencies. Afterwards,
 * it enables all the dependenicies except the onence specified as 'widgets' in the make file
 *
 * @param $make_file
 *      full path name to the make file
 */
function configure_all_dependencies_action($make_file)
{

    //grab all dependencies from make file
    $widget_dependencies = get_widget_dependencies_action($make_file);
    $project_dependencies = get_project_dependencies_action($make_file);
    $all_dependent_modules = (isset($project_dependencies) && is_array($project_dependencies)) ? array_unique(array_merge($widget_dependencies, $project_dependencies)) : $widget_dependencies;


    /*****DEBUG******/
    if (DEBUG_ON) {
        drush_print('All Dependent MODULES');
        print_r($all_dependent_modules);
    }

//make only the ones disabled or uninstalled to be enabled
    $currently_enabled = drush_invoke_process("@self", "pm-list", array(), array('pipe' => 'yes', 'type' => 'module', 'status' => 'Enabled')) ? : array();
    $cur_enabled = empty($currently_enabled['object']) ? array() : array_keys($currently_enabled['object']);
    $all_not_enabled = array_diff($all_dependent_modules, $cur_enabled);
//$depenencies_not_enabled = array_intersect(array_values($all_not_enabled), array_values($all_dependent_modules));
    $depenencies_not_enabled = array_intersect($all_dependent_modules, $all_not_enabled);

    if (DEBUG_ON) {
        drush_print('Currently Inactive From Dependencies');
        print_r($depenencies_not_enabled);
    }

//enable inactive dependencies
    foreach ($depenencies_not_enabled as $key => $dependent_module) {
        if (DEBUG_ON) {
            _log('enabling...');
            print_r($dependent_module);
            drush_print("\n");
        }
        //@ToDo fix the bug for 'features_extra' break
        if ($dependent_module != 'features_extra') {
            //download first if module with different version than stable
            $name_tockens = explode('-', $dependent_module);
            if (count($name_tockens) > 1) {
                //
                _log('module ' . $dependent_module . ((drush_invoke_process("@self", "pm-download", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' downloaded');
                //strip version, so it can be enabled
                $dependent_module = array_shift($name_tockens);
            }

            _log('module ' . $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
        }
    }
}


/*
 * Reads make file and downloads all the dependencies specified with 'widget'
 *
 * @param $make_file
 *      full path to make_file
 *
 * @return: array
 *      module names declared in make file with 'widget' tag
 */
function download_widget_dependencies_action($make_file)
{
    //download all projects specified in the .make file
    $widget_modules = get_widget_dependencies_action($make_file);

    foreach ($widget_modules as $key => $module) {
        //download
        _log('about do download ' . $module . "...");
        _log('module ' . $module . ((drush_invoke_process("@self", "pm-download", array($module)) ? ' WAS ' : ' WAS NOT')) . ' downloaded');
    }
}

/*
 * Reads make file and downloads all the dependencies specified with 'project'
 *
 * @param: $make_file
 *      full path to make_file
 *
 * @return: array
 *     module names declared in make file with 'project' tag
 */
function download_project_dependencies_action($make_file)
{
    //download all projects specified in the .make file
    $project_modules = get_project_dependencies_action($make_file);

    foreach ($project_modules as $key => $module) {
        //download
        _log('about do download ' . $module . "...");
        _log('module ' . $module . ((drush_invoke_process("@self", "pm-download", array($module)) ? ' WAS ' : ' WAS NOT')) . ' downloaded');
    }
}

/*
 * retrieves project dependencies
 *
 * @param $make_file
 *      full path to make_file
 *
 * @return: array
 *      module names declared in make file with 'project' tag
 */
function get_project_dependencies_action($make_file)
{
    if (!file_exists($make_file)) {
        drush_die("Make File - " . $make_file . " does not exist");
    }
    //download all projects specified in the .make file
    $build_file_parsed = drupal_parse_info_file($make_file);
    $project_modules = _project_modules_from_make($build_file_parsed);

    return $project_modules;
}

/*
 * reads make file for all widgets declared with 'widget' tag. returns the list after removing widget dependent on the another widget
 *
 * @param: $make_file
 *      path to make file
 * @return: array of module names that widgets dependent
 */
function get_widget_dependencies_action($make_file)
{
    if (!file_exists($make_file)) {
        drush_die("Make File - " . $make_file . " does not exist");
    }
    $build_file_parsed = drupal_parse_info_file($make_file);

    $widget_modules = _array_column(array_orderby($build_file_parsed['widget'], 'order', SORT_ASC), "name");
    $all_dependent_modules = array();

    //retrieving dependency list
    drush_include_engine('drupal', 'environment', drush_drupal_major_version());
    $module_info = drush_get_modules();
    $dependency_status = drush_check_module_dependencies($widget_modules, $module_info);
    $dependencies_per_widget = _array_column($dependency_status, 'dependencies');
    foreach ($dependencies_per_widget as $key => $mod_dependancies) {
        $all_dependent_modules = (isset($mod_dependancies) && is_array($mod_dependancies)) ? array_unique(array_merge($all_dependent_modules, $mod_dependancies)) : $all_dependent_modules;
    }

    /*****DEBUG******/
    if (DEBUG_ON) {
        drush_print('Widget Modules:');
        print_r($widget_modules);
        drush_print('All Dependent MODULES');
        print_r($all_dependent_modules);
    }
    //remove widget modules for being enabled
    $dependencies_without_widgets = array_diff($all_dependent_modules, $widget_modules);
    return $dependencies_without_widgets;
}


/*
 * Link Action creates symbolic from one location to another
 * @param: $src_dir
 *      source directory
 * @param: $dest_dir
 *      destination directory
 */

function link_action($src_dir,$dest_dir){

    if(!file_exists($src_dir)){
        _log("ERROR: source location - ".$src_dir." does not exist");
    }


//    if(file_exists($dest_dir)){
        _log("in case destination - ".$dest_dir." already exist....lets try to delete");
        execute_command('sudo rm -R '.$dest_dir);
//    }

    execute_command('sudo ln -s '.$src_dir.' '.$dest_dir);
//    (symlink($src_dir, $dest_dir)) ? _log('linking '.$src_dir.' to  '.$dest_dir) : _log("***ERROR: didn't linked to ".$dest_dir);
}

/*
 * Enables module by name $module_name
 *
 * @param $module_name
 *      the name of module
 *
 * action[enable-context-ui][name] = enable_module
 * action[enable-context-ui][order] = 1
 * action[enable-context-ui][param][module-name] = context_ui
 *
 */
function enable_module_action($module_name){
    //@ToDo check if module is not present, then download
    _log('module ' . $module_name . ((drush_invoke_process("@self", "pm-enable", array($module_name)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}

/*
 * Set variable
 *
 * @param: $name
 *      the name of variable
 * @param: $value
 *      the value of variable
 */
function set_action($name, $value){
    variable_set($name, $value);
    _log('variable "'.$name.'" was set to "'.$value.'"');
//    _log('variable - ' . $name . ((drush_invoke_process("@self", "vset", array($name,$value)) ? ' WAS ' : ' WAS NOT')) . ' set to '.$value);
}

/*
 * different configurations for tuning site to production. Here is the list
 * 1. Remove Update messages(disable "update" module) to stop security messages
 * 2. Turn off messages
 * 3. @ToDo turn on file caching and aggrigation
*/
function get_production_ready_action(){
    //turn off update module
    _log('module - "update" '.((drush_invoke_process("@self", "dis", array('update')) ? ' WAS ' : ' WAS NOT')) . ' disabled');

    //turn off messages
    set_action('error_level',0);
}

/*Enables jquery_update module and sets version
 *
 * @param: $ver
 *      jQuery lib version
 * @param: $compression
 *      set whether compressing the jquery or not
 *
 * */
function configure_jquery_action($ver = "1.10", $compression = "min"){

    _log('configuring jquery_update...');
    enable_module_action('jquery_update');
    set_action('jquery_update_compression_type', "min");
    set_action('jquery_update_jquery_cdn',"google");
    set_action('jquery_update_jquery_admin_version',"1.10");
    set_action('jquery_update_jquery_version',"1.10");
}