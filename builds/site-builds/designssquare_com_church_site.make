api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;custom modules
widget[1][name]= designssquare_com_site_church_configurations_data_structures
widget[1][order]=5
widget[2][name]=designssquare_com_site_church_data_assets
widget[2][order]=6
widget[3][name]=designssquare_lib
widget[3][repo]=kapasoft-config-scripts/designssquare-utils
widget[3][order]=1
widget[4][name]=designssquare_lib_sample_data
widget[4][order]=7
widget[5][name]=designssquare_com_site_church
widget[5][order]=8



;;themes
theme[1][name]=bootstrap
theme[1][order]=1
theme[2][name]=builder
theme[2][order]=2

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

;;for new menu exporter
projects[uuid][version] = 1.x-dev
projects[uuid_features][version] = 1.x-dev
;projects[] = uuid
;projects[] = uuid_features