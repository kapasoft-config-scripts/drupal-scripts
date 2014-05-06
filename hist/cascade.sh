#build Destribution
bda cascade 0.7 theme yes

###TEST
#install test site
si test-cascade build-drupal-dev-site.make                                                                                                            master 225d3be+

#install theme calypso
bta cascade 0.7 theme

