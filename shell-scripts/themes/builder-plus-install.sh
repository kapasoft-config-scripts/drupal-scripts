#!/bin/zsh
source /Users/maxit/shared-zshrc/zshrc_general

daa builder 0.5 theme "no" "yes" "$1"
cad builder theme dev "yes"

daa slider 0.11 module no no "$1"
#cad slider module dev
