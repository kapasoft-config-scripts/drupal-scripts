#Installing
si espresso build-drupal-dev-site.make

#Build Distr
bda espresso 0.2 theme no yes

#Deploy
bta espresso 0.2 theme

#Deploy blog widget
daa blog 0.1.9 module min