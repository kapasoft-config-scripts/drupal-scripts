api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;blog module
widget[3][name] = designssquare_com_ckeditor
widget[3][order]=3
widget[0][name]=designssquare_com_utils_ckeditor
widget[0][order]=1

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

 ;;for JW Player
 ;projects[] = jw_player
 ;projects[] = video
 ;projects[] = video_presets