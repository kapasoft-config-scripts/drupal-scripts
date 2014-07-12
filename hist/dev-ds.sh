#install site
si designs-square build-drupal-dev-site.make

#jump into root dir
cd dev-ds

#install artifact theme:bazar
bt bazar 0.4.5 theme

#configure dependencies for artifacte theme:bazar
cad bazar theme dev

#configure artifact theme:bazar
cas bazar theme