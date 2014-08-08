api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;blog module
widget[0][name]=designssquare_com_blog
widget[0][order]=1
widget[1][name]=designssquarecom_blog_structure
widget[1][order]=0
widget[2][name]=designssquarecom_blog_example_data
widget[2][order]=2
widget[3][name] = designssquare_lib_sample_data
widget[3][order]=3
widget[4][name] = designssquare_com_ckeditor
widget[4][order]=4
widget[5][name] = designssquarecom_blog_context
widget[5][order]=5
widget[11][order]=11
widget[11][name]=designssquare_lib

;;action[get_widget_make][name]=get_widget_make
;;action[get_widget_make][order]=0
;;action[get_widget_make][result]=widget_make_file

;;action[config_depend][name]=configure_all_dependencies
;;action[config_depend][order]=1
;;action[config_depend][make_file]=widget_make_file

;;action[configure_audio][name]=configure_audio
;;action[configure_audio][order]=3
;;action[configure_audio][param][module_name]=designssquare_lib
;;action[configure_video][param][audio-player]=wpaudioplayer

action[configure_editor][name] = configure_editor
action[configure_editor][order]=5
action[configure_editor][param][module_name]=designssquare_com_ckeditor
;;action[configure_editor][param][editor]=ckeditor


;;action[configure_video][name]=configure_video
;;action[configure_video][order]=4
;;action[configure_video][param][module_name]=designssquare_lib
;;action[configure_video][param][vidoe-lib]=video-js


;;dev modules
;projects[] = module_filter
;projects[] = devel
;projects[] = simplehtmldom
;projects[simplehtmldom][version] = 1.12
;projects[] = devel_themer
;projects[] = coffee
;projects[] = diff
;projects[] = admin_menu
;projects[] = features_diff


;;module related
 projects[] = audiofield
 projects[] = context
 projects[] = videojs
 projects[] = libraries
 projects[] = strongarm
 projects[uuid_features][version] = 7.x-1.x
 projects[uuid][version] = 7.x-1.x
 projects[] = views
 projects[] = entity
 projects[] = ctools
 projects[] = features
 projects[] = ckeditor
 projects[] = imce
 projects[] = vidoejs

 ;;for JW Player
 ;projects[] = jw_player
 ;projects[] = video
 ;projects[] = video_presets