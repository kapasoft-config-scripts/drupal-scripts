api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;custom modules
widget[1][name]= designssquare_com_site_loam_structure
widget[1][order]=5
widget[2][name]=esignssquare_com_site_loam_data
widget[2][order]=6
widget[3][name]=designssquare_lib
widget[3][order]=1
widget[4][name]=designssquare_com_site_loam
widget[4][order]=7
widget[5][name]=designssquare_lib_sample_data
widget[5][order]=4
widget[7][name]=designssquare_com_theme_blocks_plus
widget[7][order]=3
widget[8][name]=blog
widget[8][order]=8
widget[9][name]=gallery
widget[9][order]=9
widget[10][name]=service
widget[10][order]=10
widget[11][name]=ckeditor
widget[11][order]=11
widget[12][name]=slider
widget[12][order]=12
widget[13][name]=builder_theme_plus
widget[13][order]=13


;;themes
theme[1][name]=bootstrap
theme[1][order]=1
theme[2][name]=builder
theme[2][order]=2

action[get_widget_make][name]=get_widget_make
action[get_widget_make][order]=0
action[get_widget_make][result]=widget_make_file

action[config_depend][name]=configure_all_dependencies
action[config_depend][order]=1
action[config_depend][make_file]=widget_make_file

action[configure_audio][name]=configure_audio
action[configure_audio][order]=3
action[configure_audio][param][module_name]=designssquare_lib
;;action[configure_video][param][audio-player]=wpaudioplayer

action[configure_editor][name] = configure_editor
action[configure_editor][order]=5
action[configure_editor][param][module_name]=designssquare_com_ckeditor
;;action[configure_editor][param][editor]=ckeditor

action[configure_video][name]=configure_video
action[configure_video][order]=4
action[configure_video][param][module_name]=designssquare_lib
;;action[configure_video][param][vidoe-lib]=video-js



;;dev modules
projects[] = module_filter
projects[] = devel
;projects[] = simplehtmldom
projects[simplehtmldom][version] = 1.12
projects[] = devel_themer
projects[] = coffee
projects[] = diff
projects[] = admin_menu
projects[] = features_diff



;;site related
projects[jquery_update][version] = 2.x-dev
projects[] = audiofield
projects[] = context
projects[] = ctools
projects[] = features
projects[] = libraries
projects[] = strongarm
projects[] = features_extra
projects[] = views
projects[] = entity
projects[] = entityreference
projects[] = image_url_formatter
projects[] = feeds_tamper
projects[] = calendar
projects[] = date

;;for new menu exporter
projects[uuid][version] = 1.x-dev
projects[uuid_features][version] = 1.x-dev