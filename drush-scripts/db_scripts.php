#!/usr/bin/env drush
<?php
/********SAMPLEs*****/
//sudo drush ../config/drush-scripts/revert_changes.php --revert-all-files
//sudo drush ../config/drush-scripts/revert_changes.php --drop-tables --delete-root
//sudo drush ../config/drush-scripts/revert_changes.php --revert-untracked-files

//include_once dirname(__FILE__).'/includes/drush-script-extensions.inc';
//include_once getcwd().'/../../includes/drush-script-extensions.inc';

include_once 'includes/drush-script-extensions.inc';

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

drush_print("Time to prepare the working environment.");


//Check to see if we should back things up first.
if (drush_get_option('backup-db')) {
    drush_print("BACKING UP DB...");
    if (_backup_stuff()) {
        drush_print("DB Backup complete.");
    }
}


if (drush_get_option('drop-db')) {
    drush_print("Droping DB...");
    if (_drop_db()) {
        drush_print("DB Dropped.");
    }
}

if (drush_get_option('import-db')) {
    drush_print("Importing DB...");
    if (_import_db()) {
        drush_print("DB Imported.");
    }
}