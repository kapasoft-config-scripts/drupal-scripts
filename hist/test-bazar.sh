
#install site
###!!!make sure to update username/password in the install script
si test-bazar build-drupal-dev-site.make

#jump into root
cd test-bazar

#install bazar theme
bt bazar 0.4.5 theme

#configure bazar theme
cs bazar test theme