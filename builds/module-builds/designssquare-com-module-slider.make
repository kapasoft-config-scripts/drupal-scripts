api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;blog module
widget[0][name]=designssquare_com_slider
widget[0][order]=1
widget[1][name]=designssquare_elastic_slide
widget[1][order]=0
widget[2][name]=designssquare_rev_slider_layer
widget[2][order]=2
widget[3][name]=designssquare_com_slider_elastic_structure
widget[3][order]=3
widget[4][name]=designssquare_com_slider_revolution_structure
widget[4][order]=4
widget[5][name]=designssquare_com_slider_elastic_sample_data
widget[5][order]=5
widget[6][name]=designssquare_com_slider_revolution_sample_data
widget[6][order]=6
widget[10][name]=designssquare_lib_sample_data
widget[10][order]=10
widget[9][name]=designssquare_lib
widget[9][order]=9

action[enable-context-ui][name] = enable_module
action[enable-context-ui][order] = 1
action[enable-context-ui][param][module-name] = context_ui

action[enable-views][name] = enable_module
action[enable-views][order] = 1
action[enable-views][param][module-name] = views_ui

action[enable-views_ui][name] = enable_module
action[enable-views_ui][order] = 1
action[enable-views_ui][param][module-name] = admin_menu

action[enable-coffee][name] = enable_module
action[enable-coffee][order] = 1
action[enable-coffee][param][module-name] = coffee

action[jquery-stage][name] = configure_jquery
action[jquery-stage][order] = 2
action[jquery-stage][param][ver] = 1.10

projects[] = field_ui
projects[] = fapi_validation
projects[] = field_remove_item
projects[] = context
