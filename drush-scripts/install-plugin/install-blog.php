#!/usr/bin/env drush
<?php
require_once  getcwd().'/../../config/drush-scripts/includes/drush-script-extensions.inc';

$package_name = 'designssquare_com_blog';
$module_name = 'blog';
$module_dir = 'sites/all/modules';
$config_dir = '/Users/maxit/Sites/drupal/config';
$make_file_name = 'designssquare_com_blog.make';
$src_dir = '/Users/maxit/Sites/drupal/modules';
$debug = true;

$module_src = $src_dir.'/'.$module_name;
$make_file = $config_dir.'/mod-builds/'.$make_file_name;


// check if we can bootstrap
$self = drush_sitealias_get_record('@self');
if (empty($self)) {
    drush_die("I can't bootstrap from the current location.", 0);
}

//let's jump to our site directory before we do anything else
drush_op('chdir', $self['root']);

//link to widget
drush_print('Linking modules....');
$widget_name = 'sites/all/modules/'.$package_name;
if(!file_exists($module_src)){
    drush_die("path to source module does not exist: ".$module_src, 0);
}
drush_print('linking modules named '.$widget_name.' to path '.$module_src);
(symlink($module_src, $widget_name)) ? 'linking widget named '.$widget_name.' to path '.$module_src : "***ERROR: didn't limked to".$path_to_module;

//download dependencies
//$widget_make_file = $module_src.'/'.$package_name.'.make';
//if(!file_exists($widget_make_file)){
//    drush_die("path to widget make file does not exist: ".$widget_make_file, 0);
//}
//$opt['no-core'] = TRUE;
//$opt['yes'] = TRUE;
//$opt['contrib-destination'] = '' ;
//_process_make_file($widget_make_file,Null,$opt);

//initilize
$build_file_parsed = drupal_parse_info_file($make_file);
$widget_modules = _array_column(array_orderby($build_file_parsed['widget'], 'order', SORT_ASC),"name");
$all_dependent_modules = array();
$missing_dependent_modules = array();

//retrieving dependency list
drush_include_engine('drupal', 'environment', drush_drupal_major_version());
$module_info =  drush_get_modules();
$dependency_status = drush_check_module_dependencies($widget_modules, $module_info);
$all_dependent_modules = array();
foreach(_array_column($dependency_status,'dependencies') as $key => $mod_dependancies){
    $all_dependent_modules = array_merge($all_dependent_modules,$mod_dependancies);
}

/*****DEBUG******/
if($debug){
    drush_print('Widget Modules:');
    print_r($widget_modules);
    drush_print('Dependency Status:');
    print_r($dependency_status);
    drush_print('All Dependent MODULES');
    print_r($all_dependent_modules);
}

//remove widget modules for being enabled
$dependencies_without_widgets = array_diff($all_dependent_modules, $widget_modules);
if($debug){
    drush_print('Dependencies Without Widgets:');
    print_r($dependencies_without_widgets);
}

//@ToDo grab the dev modules from make file perhaps
//Adding modules to enable besides the default
//$all_dependent_modules[] = 'commerce_cart';
//$all_dependent_modules[] = 'commerce_checkout';
//$all_dependent_modules[] = 'commerce_ui';
//$all_dependent_modules[] = 'commerce_customer';
//$all_dependent_modules[] = 'commerce_customer_ui';
//$all_dependent_modules[] = 'commerce_line_item';
//$all_dependent_modules[] = 'commerce_line_item_ui';
//$all_dependent_modules[] = 'commerce_order';
//$all_dependent_modules[] = 'commerce_order_ui';
//$all_dependent_modules[] = 'commerce_payment';
//$all_dependent_modules[] = 'commerce_payment_ui';
//$all_dependent_modules[] = 'commerce_price';
//$all_dependent_modules[] = 'commerce_product';
//$all_dependent_modules[] = 'commerce_price';
//$all_dependent_modules[] = 'commerce_product_pricing';
//$all_dependent_modules[] = 'commerce_product_pricing_ui';
//$all_dependent_modules[] = 'commerce_product_reference';
//$all_dependent_modules[] = 'commerce_product_ui';
//$all_dependent_modules[] = 'commerce_product_reference';
//$all_dependent_modules[] = 'commerce_tax';
//$all_dependent_modules[] = 'commerce_tax_ui';

//$all_dependent_modules[] = 'media';


//enable dependencies
drush_print('ENABLING DEPENDENT MODULES ...');
foreach($dependencies_without_widgets as $key => $dependent_module){
    drush_print('module '. $dependent_module . ((drush_invoke_process("@self", "pm-enable", array($dependent_module)) ? ' WAS ' : ' WAS NOT')) . ' enabled');
}


//Link JWPlayer
//link to widget
drush_print('Linking jwplayer....');
$player_dest = 'sites/all/libraries/jwplayer';
$payer_src = 'sites/all/modules/'.$package_name.'/jwplayer';
if(!file_exists($payer_src)){
    drush_die("path to source module does not exist: ".$payer_src, 0);
}
drush_print('linking player '.$player_dest.' to path '.$payer_src);
(symlink($payer_src, $player_dest)) ? 'linking widget named '.$player_dest.' to path '.$payer_src : "***ERROR: didn't limked to".$payer_src;
