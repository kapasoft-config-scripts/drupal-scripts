

#site install
sudo drush ../config/drush-scripts/install_site.php --build-dest=calypso --build-src=../config/builds/build-drupal-stage-site.make

#run from root dir
cd calypso

#building artifacts
sudo drush ../../config/drush-scripts/build-stage-scripts/build-calypso-theme-stage.php

#configuring calypso theme
sudo drush ../../config/drush-scripts/configure-stage-scripts/configure-calypso-theme-stage.php