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