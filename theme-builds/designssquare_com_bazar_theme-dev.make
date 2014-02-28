api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;theme
;projects[designssquare_com_blog][type] = "module"
;projects[designssquare_com_blog][subdir] = contrib
;projects[designssquare_com_blog][version] = 7.x-1.0
;projects[designssquare_com_blog][download][type] = "git"
;projects[designssquare_com_blog][download][url] = "http://github.com/kapasoft-drupal-modules/blog.git"
;projects[designssquare_com_blog][download][branch] = "master"
;widget[0][name]=designssquarecom_blog_structure
;widget[0][order]=1
;widget[1][name]=designssquarecom_blog_example_data
;widget[1][order]=2
;widget[2][name]=designssquare_com_blog
;widget[2][order]=0
;projects[designssquare_com_commerce][type] = "module"
;projects[designssquare_com_commerce][subdir] = contrib
;projects[designssquare_com_commerce][version] = 7.x-1.0
;projects[designssquare_com_commerce][download][type] = "git"
;projects[designssquare_com_commerce][download][url] = "http://github.com/kapasoft-drupal-modules/commerce.git"
;projects[designssquare_com_commerce][download][branch] = "master"
;widget[3][name]=designssquare_com_commerce
;widget[3][order]=3
;projects[bazar_theme][type] = "module"
;projects[bazar_theme][subdir] = contrib
;projects[bazar_theme][version] = 7.x-1.0
;projects[bazar_theme][download][type] = "git"
;projects[bazar_theme][download][url] = "http://github.com/kapasoft-drupal-modules/bazar_theme.git"
;projects[bazar_theme][download][branch] = "master"
widget[4][name]=bazar_theme
widget[4][order]=4
widget[5][name]=designssquare_com_theme_bazar_structure
widget[5][order]=5
;projects[bazar][type] = "theme"
;projects[bazar][version] = 7.x-1.0
;projects[bazar][download][type] = "git"
;projects[bazar][download][url] = "https://github.com/kapasoft-drupal-themes/bazar.git"
;projects[bazar][download][branch] = "master"

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
projects[] = uuid
projects[] = uuid_features
projects[] = features_extra
projects[] = views
projects[] = entity
projects[] = entityreference
projects[] = image_url_formatter
projects[] = feeds_tamper


;;for new menu exporter
;;projects[uuid][version] = 1.x-dev
;;projects[uuid_features][version] = 1.x-dev