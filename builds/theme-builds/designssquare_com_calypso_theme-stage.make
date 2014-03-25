api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;theme
widget[0][name] = designssquare_com_theme_calypso_structure
widget[0][order] = 1
widget[1][name] = designssquare_com_theme_calypso_data
widget[1][order] = 2
widget[2][name] = designssquare_com_calypso_main_menu_importer
widget[2][order] = 3
widget[3][name] = designssquare_com_calypso_bottom_menu_importer
widget[3][order] = 4

;;theme related
;projects[jquery_update][version] = 2.x-dev
projects[] = jquery_update
projects[] = pathauto

;;for new menu exporter
projects[uuid][version] = 1.x-dev
projects[uuid_features][version] = 1.x-dev
;projects[] = uuid
;projects[] = uuid_features