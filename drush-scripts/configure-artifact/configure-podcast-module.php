#!/usr/bin/env drush
<?php
require_once  '../../config/drush-scripts/includes/drush-script-extensions.inc';
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$artifact_name = drush_get_option('artifact-name');
$artifact_type = drush_get_option('artifact-type','module');
$artifact_dir = 'designssquare_com_widget_'.$artifact_name;
$module_dir = 'sites/all/modules';
$theme_dir = 'sites/all/themes';
$src_dir = '/Users/maxit/Sites/drupal';
$debug = true;


// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

//configure audio
variable_set('audiofield_audioplayer', "wpaudioplayer");
$payer_audio_src = "sites/all/modules/$artifact_dir/libraries/player";
if(!file_exists($payer_audio_src)){
    drush_die("path to audo player lib does not exist: ".$payer_audio_src, 0);
}
variable_set('audiofield_players_dir', $payer_audio_src);
drush_print('...configured audio player lib to path '.$payer_audio_src);

drush_print('Done configuring '.$artifact_type.' - '.$artifact_name);
