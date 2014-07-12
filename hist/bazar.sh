
#install site
###!!!make sure to update username/password in the install script
si test-bazar build-drupal-dev-site.make

#jump into root
cd test-bazar

#building distribution
bda bazar 0.4.6 theme

#install bazar theme
daa bazar 0.4.6 theme

#configure bazar theme
cs bazar test theme