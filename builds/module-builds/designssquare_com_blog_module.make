api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;blog module
widget[4][name] = designssquarecom_blog_context
widget[4][order]=4
widget[0][name]=designssquare_com_blog
widget[0][order]=1
widget[1][name]=designssquarecom_blog_structure
widget[1][order]=0
widget[2][name]=designssquarecom_blog_example_data
widget[2][order]=2
widget[3][name] = designssquare_lib_sample_data
widget[3][order]=3
widget[11][order]=11
widget[11][name]=designssquare_lib
widget[11][repo]=kapasoft-config-scripts/designssquare-lib
widget[11][order]=11
widget[11][name]=designssquare_lib

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