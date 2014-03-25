#!/usr/bin/env drush
<?php
/********SAMPLEs*****/
//sudo drush ../config/drush-scripts/revert_changes.php --revert-all-files
//sudo drush ../config/drush-scripts/revert_changes.php --drop-tables --delete-root
//sudo drush ../config/drush-scripts/revert_changes.php --revert-untracked-files

require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

drush_print("Time to prepare the working environment.");


 //let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

if (drush_get_option('revert-files', FALSE)) {
    drush_print("REVERTING FILES...");
    drush_op_system('git checkout .');
}

if (drush_get_option('revert-untracked-files', FALSE)) {
    drush_print("REVERTING FILES...");
    drush_op_system('git reset --hard');
}

if (drush_get_option('revert-all-files', FALSE)) {
    drush_print("REVERTING FILES...");
    drush_op_system('git clean -xdf');
}

if (drush_get_option('drop-tables', FALSE)) {
    drush_print("dropping tables for database ...");
    $creds = drush_get_context('DRUSH_DB_CREDENTIALS');
//    drush_op_system('../config/shell-scripts/drop-tables.sh '.$creds['user'].' '.$creds['pass'].' '.$creds['name']);

    //remove setting.php so it Drupal code base can be run without trying to connect with DB
//    $handle = sqlite('sites/default/settings.php');
//    unset($handle);
//    @unlink('sites/default/settings.php');
//    print_r($creds);
    $status = drush_invoke_process('@self', 'sql-drop', array(), array('db-url' => 'mysql://'.$creds['user'].':'.$creds['pass'].'@'.$creds['host'].':' . $creds['port'] . '/'.$creds['name'], 'yes' => TRUE));
    drush_print((($status) ? 'Tables were dropped' : 'FAILED to drop tables'));
}

if (drush_get_option('delete-root', FALSE)) {
    print('#removing dir.....');
    drush_shell_exec('rm -R '.$self['root']);
//    rmdir(getcwd());
}

if (drush_get_option('rollback-db', FALSE)) {
    drush_print("ROLLING BACK DATABASE ...");
    drush_op_system('gradle importDb');
}

if (drush_get_option('revert-untracked-files',FALSE)) {
    drush_print("DELETING UNTRACKED MODULES ...");
    drush_shell_exec('git ls-files -o');
    $untracked_files = drush_shell_exec_output();
    foreach($untracked_files as $key => $file_path){
    $pos = strpos($file_path, 'sites/all/modules');
    if($pos === 0){
//       drush_print('file '. $file_path. ((drush_shell_exec('rm -d '.$file_path)) ? ' WAS ' : ' WAS NOT ').'removed');
               drush_print('file '. $file_path. ((unlink($file_path)) ? ' WAS ' : ' WAS NOT ').'removed');
        //if last file is deleted than also remove directory
            $dir = dirname($file_path);
            $files = glob($dir.'/*');
            if(empty($files)){
                drush_print('dir '.$dir.((rmdir($dir)) ? ' WAS ' : ' WAS NOT ').'deleted');
                //remove parent directories that are empty
                $parent_dir = dirname($dir);
                $files = glob($parent_dir.'/*');
                while($parent_dir !== 'sites/all/modules' && empty($files)){
                    drush_print('parent dir '.$parent_dir.((rmdir($parent_dir)) ? ' WAS ' : ' WAS NOT ').'deleted');
//                    drush_print('Par Dir: ' . $parent_dir . ' to Delete....'.base_path().'sites/all');
                    $parent_dir = dirname($parent_dir);
                    $files = glob($parent_dir.'/*');
                }
            }
    }
 }
}



?>
