#!/bin/bash

OLDWD=`pwd`

cd "$(dirname "$0")"/locales/templates

TMPFILE=/tmp/ehRepeat.intl.files

find ../../ -name "*.php" >$TMPFILE
xgettext -k'__'  -k'__:1,2'  -o messages.pot -j --from-code=UTF-8 -f $TMPFILE

find ../../ -name '*.tpl'>$TMPFILE
xgettext -k'__'  -k'__:1,2' -LPHP -o messages.pot -j --from-code=UTF-8 -f $TMPFILE

cd ../fr

msgmerge -U --backup=simple --suffix=.old -F -i main.po ../templates/messages.pot 

unlink $TMPFILE

nohup bluefish main.po  &

cd $OLDWD
