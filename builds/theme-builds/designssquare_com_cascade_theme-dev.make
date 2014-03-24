api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;theme
;projects[designssquare_com_theme_cascade][type] = "module"
;projects[designssquare_com_theme_cascade][subdir] = contrib
;projects[designssquare_com_theme_cascade][version] = 7.x-1.0
;projects[designssquare_com_theme_cascade][download][type] = "git"
;projects[designssquare_com_theme_cascade][download][url] = "https://github.com/kapasoft-drupal-modules/theme_cascade.git"
;projects[designssquare_com_theme_cascade][download][branch] = "master"
;widget[0][name]=designssquare_com_theme_cascade_structure
;widget[0][order]=1
;widget[1][name]=designssquare_com_theme_cascade_data
;widget[1][order]=2
;widget[2][name]=designssquare_com_menu_importer_cascade
;widget[2][order]=3
;projects[cascade][type] = "theme"
;projects[cascade][version] = 7.x-1.0
;projects[cascade][download][type] = "git"
;projects[cascade][download][url] = "https://github.com/kapasoft-drupal-themes/theme_cascade.git"
;projects[cascade][download][branch] = "master"

projects[bootstrap][type] = "theme"
projects[bootstrap][version] = 7.x-1.0
projects[bootstrap][download][type] = "git"
projects[bootstrap][download][url] = "https://github.com/kapasoft-drupal-themes/bootstrap.git"
projects[bootstrap][download][branch] = "master"

;;dev modules
projects[] = module_filter
projects[] = devel
projects[] = simplehtmldom
projects[] = devel_themer
projects[] = coffee
projects[] = diff
projects[] = admin_menu
projects[] = features_diff

;;wysiwyg
;projects[] = ckeditor

;;other
;projects[] = media

;;theme related
projects[jquery_update][version] = 2.x-dev
projects[] = audiofield
projects[] = context
projects[] = ctools
projects[] = features
projects[] = libraries
projects[] = strongarm
;projects[] = uuid
;projects[] = uuid_features
projects[] = features_extra
projects[] = views
projects[] = entity
projects[] = entityreference


;;for new menu exporter
projects[uuid][version] = 1.x-dev
projects[uuid_features][version] = 1.x-dev
