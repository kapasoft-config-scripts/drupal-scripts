#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$output_dir = ($output_dir_arg = drush_get_option('output_dir', FALSE)) ? $output_dir_arg  : "/scripts-output";
$src_dir = ($src_dir_arg = drush_get_option('output_dir', FALSE)) ? $src_dir_arg  : "/scripts-source";

//$image = "/photo/test.JPG";
//$org_info = getimagesize("/photo/test2.JPG");
//drush_print('<br>Before: <br>'.$org_info[3]);


$dir = '/Users/maxit/Documents/Other/Properties/Druva/photos/selected-min';
//$parent_dir = dirname($dir);
//$files = glob($parent_dir.'/*');
//print_r($files);
//foreach($files as $file ){
//    //do something
//    print_r($file);
////    drush_shell_exec('sips -z 768 1024 /photo/test2.JPG');
//}

if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
        if($file !== '.' && $file !== '..'){
            drush_print($dir.'/'.$file);
                drush_shell_exec('sips -z 768 1024 '.$dir.'/'.$file);
        }
        // do something with the file
        // note that '.' and '..' is returned even
    }
    closedir($handle);
}


//drush_print('<br>Before: <br>'.$org_info[3]);
//$rsr_org = imagecreatefromjpeg($image);
//$rsr_scl = imagescale($rsr_org, 860, 860,  IMG_BICUBIC_FIXED);
//drush_print('Done scaling...creating...');
//imagejpeg($rsr_scl, "/photo/output/imagebfb.jpg");
//drush_print('Done creating...destroying...');
//imagedestroy($rsr_org);
//drush_print('Done destroying...done...');
//imagedestroy($rsr_scl);
//

$org_info = getimagesize("/photo/test2.JPG");
drush_print('<br>After: <br>'.$org_info[3]);

//$scl_info = getimagesize("imagebfb.jpg");
//echo $scl_info[3];
