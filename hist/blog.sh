#Installing
si blog build-drupal-dev-site.make

#Build Dist @ToDo refactor into 'build dist any - bda' script
bda blog 0.1.8 module
bda blog 0.1.9 module min

#configure blog
cs blog test module

#Deplay on test env
bta blog 0.1.8 module
daa blog 0.1.9 module min
daa blog 0.1.9 module

#dev environment
bta builder 0.4 theme

