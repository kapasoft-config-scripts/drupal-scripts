si church build-drupal-dev-site.make

daa espresso 0.4 theme no yes
cad espresso module dev

daa blog 0.1.12 module yes no
#configure blog
#cs blog test module
cad blog module test

daa newsletter 0.1 module yes no
cad newsletter module test
daa podcast 0.1 module yes no
cad podcast module test
daa gallery 0.1 module yes no
cad gallery module test

daa church 0.1 site no no


/***********script*********/
#!/bin/zsh
source /Users/maxit/shared-zshrc/zshrc_general

daa newsletter 0.1 module "yes" "no" "stage"
cad newsletter module test

daa espresso 0.4 theme "no" "yes" "stage"
cad espresso theme "dev" "yes"

daa blog 0.1.12 module "yes" "no" "
cad blog module test "no"

daa podcast 0.1 module "yes" "no"
cad podcast module "stage" "no"

daa gallery 0.1 module "yes" "no" "stage"
cad gallery module test

daa church 0.1 site "no" "no" "stage"

cs ckeditor test module
cs podcast test module