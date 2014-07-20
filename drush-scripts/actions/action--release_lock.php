#!/usr/bin/env drush
<?php
//@TODo This needs to be refactored for Action

// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);


//bootsraping
$boot_status = drush_bootstrap_max_to_sitealias($self, DRUSH_BOOTSTRAP_DRUPAL_FULL);
drush_print('Bootstrap ' . (($boot_status) ? 'SUCCESS' : 'FAILED'));
drush_print('Current bootstap phase: ' . drush_get_context('DRUSH_BOOTSTRAP_PHASE'));
($errors = drush_get_context('DRUSH_BOOTSTRAP_ERRORS')) ? print_r($errors) : drush_print('no errors') ;

////perhaps it takes time to bootstrap
//while(!drush_has_boostrapped(DRUSH_BOOTSTRAP_DRUPAL_FULL)){
//    sleep(2);
//    drush_print('waiting...current phase '.drush_get_context('DRUSH_BOOTSTRAP_PHASE'));
//}

lock_release_all();