#!/bin/sh
#
# generate pot file for knowledgeroot
#
# @version $Id$

COPYRIGHT="Knowledgeroot.org"
PACKAGE_NAME="Knowledgeroot"
EMAIL="locale@knowledgeroot.org"
OUTFILE=`dirname $0`/../data/locale/knowledgeroot.pot

# find phtml templates with translations
find `dirname $0`/../app -iname "*.phtml" | xargs --no-run-if-empty xgettext --from-code=UTF-8 --default-domain=Knowledgeroot -L PHP -o $OUTFILE -ktranslate --msgid-bugs-address=$EMAIL --copyright-holder=$COPYRIGHT --package-name=$PACKAGE_NAME
find `dirname $0`/../lib -iname "*.phtml" | xargs --no-run-if-empty xgettext -j --from-code=UTF-8 --default-domain=Knowledgeroot -L PHP -o $OUTFILE -ktranslate --msgid-bugs-address=$EMAIL --copyright-holder=$COPYRIGHT --package-name=$PACKAGE_NAME

# find php files with translations
find `dirname $0`/../app -iname "*.php" | xargs --no-run-if-empty xgettext -j --from-code=UTF-8 --default-domain=Knowledgeroot -L PHP -o $OUTFILE -ktranslate --msgid-bugs-address=$EMAIL --copyright-holder=$COPYRIGHT --package-name=$PACKAGE_NAME
find `dirname $0`/../lib -iname "*.php" | xargs --no-run-if-empty xgettext -j --from-code=UTF-8 --default-domain=Knowledgeroot -L PHP -o $OUTFILE -ktranslate --msgid-bugs-address=$EMAIL --copyright-holder=$COPYRIGHT --package-name=$PACKAGE_NAME

echo Created POT file: $OUTFILE
