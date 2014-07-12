#install commerce
daa commerce 0.5 module
sudo drush ../../config/drush-scripts/build-test-scripts/build-commerce-module-test.php --ver=0.5 --artifact-name=commerce --artifact-type=module

#configure commerce
cs commerce test module
sudo drush ../../config/drush-scripts/configure-test-scripts/configure-commerce-module-test.php --env=test --artifact-name=commerce --artifact-type=module

#build distribution
bda commerce 0.7 module