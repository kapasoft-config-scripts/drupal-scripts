api = 2
core = 7.x

;;core
projects[drupal][type] = core
projects[drupal][version] = 7.26

;;commerce module
widget[0][name]=designssquare_com_commerce
widget[0][order]=1
widget[1][name]=desginssquare_com_commerce_data
widget[1][order]=2
widget[2][name]=desginssquare_com_commerce_structure
widget[2][order]=0
widget[3][name] = designssquare_sample_product_import
widget[3][order]=3

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
projects[jquery_update][version] = 2.x-dev
projects[uuid][version] = 1.x-dev
projects[uuid_features][version] = 1.x-dev
projects[] = commerce
projects[] = rules
projects[] = commerce_features
projects[] = features
projects[] = image_url_formatter
projects[] = feeds
projects[] = feeds_tamper
projects[] = job_scheduler
projects[] = commerce_feeds