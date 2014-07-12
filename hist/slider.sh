##
###!!!make sure to update username/password in the install script
si slider build-drupal-dev-site.make

###Build theme
daa bazar 0.4.6 theme
cs bazar test theme

##build package
bda slider 0.7 module yes

##deploy slider for Test
daa slider 0.10 module

#configure
cad slider module dev