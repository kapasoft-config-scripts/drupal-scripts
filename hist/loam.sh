/****DEV***/
#linked all modules/theme
#configure dependecies
cad builder theme dev

###TEST
#install test site
si loam build-drupal-dev-site.make

### build Builder theme
bta builder 0.3 theme


###Build Loam dist
bda loam 1.0 site


##Build Loam test
bta loam 1.0 site

##Configure
cad loam site test

#install Blog and configure
 4598  bt blog 0.1.7 module
 4599  cs blog test module

 #install slider
 bta slider 0.8 module

 ##STAGING
 #install stage site
 si loam build-drupal-stage-site.make

#staging Builder Theme
sudo drush ../../config/drush-scripts/build-stage-scripts/stage-any-theme.php --artifact-name=builder --artifact-type=theme
-or-
saa builder theme

#staging plugins
sudo drush ../../config/drush-scripts/build-stage-scripts/stage-any-module.php --artifact-name=slider --artifact-type=module
-or-
saa slider module
saa blog module

#stage site module
sudo drush ../../config/drush-scripts/build-stage-scripts/stage-any-site.php --artifact-name=loam --artifact-type=site
-or-
saa loam site


#Configure
sudo drush ../../config/drush-scripts/configure-dependencies.php --artifact-name=$1 --artifact-type=$2 --env=$3
cad builder theme
cad loam site
cad slider module

#for blog
cs blog test module
-or-
sudo drush ../../config/drush-scripts/configure-$2-scripts/configure-$1-$3-$2.php --env=$2 --artifact-name=$1 --artifact-type=$3
sudo drush ../../config/drush-scripts/configure-test-scripts/configure-blog-module-test.php --env=test --artifact-name=blog --artifact-type=module