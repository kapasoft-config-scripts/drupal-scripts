##
###!!!make sure to update username/password in the install script
si slider build-drupal-dev-site.make

###Build theme
bt bazar 0.4.5 theme

##build package
bda slider 0.7 module yes

##build slider for Test
bta slider 0.7 module



#configure
cad slider module dev