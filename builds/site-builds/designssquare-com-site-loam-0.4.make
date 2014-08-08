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
widget[3][ver]=v.0.2.2
widget[3][order]=1
widget[4][name]=designssquare_com_site_loam
widget[4][order]=7
widget[4][ver]=v0.3.0
widget[5][name]=designssquare_lib_sample_data
widget[5][order]=5
;;widget[5][ver]=7.x-0.1-dev
widget[7][name]=designssquare_com_theme_blocks_plus
widget[7][order]=3
widget[7][ver]=v0.1.0
widget[8][name]=blog
widget[8][order]=8
widget[8][ver]=v0.1.14
widget[9][name]=gallery
widget[9][order]=9
widget[9][ver]=v0.1.0
widget[10][name]=service
widget[10][order]=10
widget[10][ver]=v0.1.0
widget[11][name]=ckeditor
widget[11][order]=11
widget[11][ver]=v0.1.0
widget[12][name]=slider
widget[12][order]=12
widget[12][ver]=v0.11.0
widget[13][name]=builder_theme_plus
widget[13][order]=13
widget[13][ver]=v0.5.1

;;themes
theme[1][name]=bootstrap
theme[1][order]=1
theme[1][ver]=v0.1.0
theme[2][name]=builder
theme[2][order]=2
theme[2][ver]=v0.6.1


action[en_lib][name]=enable_module
action[en_lib][order]=0
action[en_lib][tag]=config,widgets
action[en_lib][param][module_name]=designssquare_lib

action[get_widget_make][name]=get_widget_make
action[get_widget_make][order]=0
action[get_widget_make][tag]=config
action[get_widget_make][result]=widget_make_file

action[config_depend][name]=configure_all_dependencies
action[config_depend][order]=1
action[config_depend][tag]=config
action[config_depend][param][make_file]=@widget_make_file

action[config_audio][name]=configure_audio
action[config_audio][order]=3
action[config_editor][tag]=config
action[config_audio][param][module_name]=designssquare_lib
;;action[configure_video][param][audio-player]=wpaudioplayer

action[config_editor][name]=configure_editor
action[config_editor][order]=5
action[config_editor][tag]=config
action[config_editor][param][module_name]=designssquare_com_ckeditor
;;action[config_editor][param][editor]=ckeditor

action[config_video][name]=configure_video
action[config_video][order]=4
action[config_video][tag]=config
action[config_video][param][module_name]=designssquare_lib
;;action[config_video][param][vidoe-lib]=video-js

action[en_blog][name]=enable_module
action[en_blog][order]=6
action[en_blog][tag]=widgets
action[en_blog][param][module_name]=designssquare_com_blog

action[en_loam][name]=enable_module
action[en_loam][order]=7
action[en_loam][tag]=widgets
action[en_loam][param][module_name]=designssquare_com_site_loam

action[en_slider][name]=enable_module
action[en_slider][order]=8
action[en_slider][tag]=widgets
action[en_slider][param][module_name]=designssquare_com_slider

action[en_rev][name]=enable_module
action[en_rev][order]=8
action[en_rev][tag]=widgets
action[en_rev][param][module_name]=designssquare_rev_slider_layer

action[en_blocks][name]=enable_module
action[en_blocks][order]=9
action[en_blocks][tag]=widgets
action[en_blocks][param][module_name]=designssquare_com_theme_blocks

action[en_builder][name]=enable_module
action[en_builder][order]=10
action[en_builder][tag]=widgets
action[en_builder][param][module_name]=designssquare_com_theme_builder

action[en_ckeditor][name]=enable_module
action[en_ckeditor][order]=11
action[en_ckeditor][tag]=widgets
action[en_ckeditor][param][module_name]=designssquare_com_ckeditor

action[en_gallery][name]=enable_module
action[en_gallery][order]=12
action[en_gallery][tag]=widgets
action[en_gallery][param][module_name]=designssquare_com_widget_gallery

action[en_service][name]=enable_module
action[en_service][order]=13
action[en_service][tag]=widgets
action[en_service][param][module_name]=designssquare_com_widget_service

action[en_sample][name]=enable_module
action[en_sample][order]=5
action[en_sample][tag]=widgets
action[en_sample][param][module_name]=designssquare_lib_sample_data

action[en_prod][name]=get_production_ready
action[en_prod][order]=14
action[en_prod][tag]=prod


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
projects[] = ckeditor
projects[] = imce
projects[] = videojs
projects[] = field_remove_item
projects[] = fapi_validation
projects[] = pathauto

;;for new menu exporter
projects[uuid][version] = 1.x-dev
projects[uuid_features][version] = 1.x-dev