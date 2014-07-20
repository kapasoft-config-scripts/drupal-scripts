#!/usr/bin/env drush
<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('memory_limit', '850M');
set_time_limit(0);


//$artifact_name = drush_get_option('artifact-name');


//read file

$filename = "ml-email-backup.mbox";
$filepath = "/Users/maxit/" . $filename;


$chunk = 10 * 1024 * 1024; // bytes per chunk (10 MB)

$f=fopen($filepath,'rb') or die("Couldn't get handle for ".$filepath);
$data='';
if ($f){
    while(!@feof($f)){
        $data .= fgets($f, 4096);
    }
    fclose($f);
}

drush_print('done reading string of size: '.mb_strlen($data, '8bit').'... start searching');

$pattern = "/,([^@]+@[^,:]+)/";
$pattern = "/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/";
//$pattern = "s/,([^@]+@[^,:]+)/g";
preg_match_all($pattern, $data, $matches);

$all_emails = array_unique(array_values($matches[0]));
$all_emails_filtered = array_filter($all_emails, 'filter_bad_emails');

print_r($all_emails_filtered);
drush_print('Count:'.count($all_emails_filtered));

drush_print('writing...');

$filename = "output-emails.csv";
$filepath = "/Users/maxit/" . $filename;

$file = fopen($filepath,"w") or die("Couldn't get handle for ".$filepath);
if($file){
    fputcsv($file, $all_emails_filtered);
}

drush_print('done');


function filter_bad_emails($email){
    $char = $email[0];
    $email_tokens = explode('@',$email);
    $domain_name = array_pop($email_tokens);
    if($char == '-' || $char == '_' || $char == '.' || is_numeric($char) ||  (strlen($email) > 20) || ($domain_name == 'mail.gmail.com') ){
        return false;
    }else{
        return true;
    }
}