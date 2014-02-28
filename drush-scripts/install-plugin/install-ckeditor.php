#!/usr/bin/env drush
<?php
$lib_name = 'ckeditor';
$lib_src = '/Users/maxit/Sites/drupal/other/'.$lib_name; //path to location of all lib
// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

//Adding Dev modules
$all_dependent_modules = array();
$all_dependent_modules[] = 'ckeditor';

drush_print('DOWNLOADING MODULES ...');
drush_print('module '. $dependent_module . ((drush_invoke_process('@self', 'dl', $all_dependent_modules) ? ' WAS ' : ' WAS NOT')) . ' downloaded');

drush_print('ENABLING DEPENDENT MODULES ...');
foreach($all_dependent_modules as $key => $dependent_module){
    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}

$lib_dir = 'sites/all/libraries';
if(!file_exists($lib_dir)){
    drush_print("libraries dir does not exist...creating one: ", 0);
    //Make libraries folder
    drush_shell_exec('sudo mkdir '.$lib_dir);
}

//link CKEditor library
$dest_lib_path = $lib_dir.'/'.$lib_name;
drush_print('linking lib from '.$dest_lib_path.' to path '.$lib_src);
(symlink($lib_src, $dest_lib_path)) ? 'linking lib from '.$dest_lib_path.' to path '.$lib_src : "***ERROR: didn't like to ".$lib_src;
