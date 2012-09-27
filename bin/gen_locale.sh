#!/bin/sh
#
# Basic code from:
# http://alioth.debian.org/frs/download.php/974/gosa-2.3.tar.bz2
# gen_locale.sh
#
# @version $Id$

usage () {
cat <<EOF
Usage: $0 [OPTIONS]
Options:
  -d    Create languages for knowledgeroot.
  -e    Extension name (without a path)
        Is this set, language files for this extension will created.
  -h    display this help and exit

EOF
exit 1
}

case $1 in
    -e) EXTENSION_NAME=$2 
    
        if [ $EXTENSION_NAME -a -d `pwd`"/../../extension/$EXTENSION_NAME/" ]; then
          # Pfad zu Sprachverzeichnis 
          # betrachtet aus knowledgeroot/
          # for extensions: 
          KR_LANGUAGE_PATH=system/extension/$EXTENSION_NAME/language
          
          # extension path 
          KR_EXTENSION_PATH=system/extension/$EXTENSION_NAME
        else 
          echo -e "\n>> Extension '$EXTENSION_NAME' not exists in `pwd`'/../../extension/'\nAbort!\n"
          exit 0
        fi;;
    -d) # Pfad zu Sprachverzeichnis 
        # betrachtet aus knowledgeroot/
        KR_LANGUAGE_PATH=system/language
        EXTENSION_NAME=knowledgeroot;;
    
    *h*) usage ;;
    *)   usage ;;
esac

# betrachtet aus knowledgeroot/system/php-gettext/tools
# dem Aufrufort dieser Datei
# ToDo: Kann man das flexibler gestallten?
KR_BASE_PATH=.
#KR_BASE_PATH=.

# *.po-Datei (lockated in $KR_LANGUAGE_PATH/<lang>/LC_MESSAGES/)
KR_POFILE=$EXTENSION_NAME.po

# *.pot-Datei
KR_POTFILE=$EXTENSION_NAME.pot

# *.pot-Pfad
KR_POTFILE_PATH=system/language

# xgettext Einstellungen
# Ausgabedatei
#XGT_TEMPLATE="knowledgeroot.pot"
XGT_TEMPLATE=$KR_POTFILE_PATH/$KR_POTFILE
# --msgid-bugs-address=EMAIL@ADDRESS
XGT_MSGIDBUGSADDRESS='--msgid-bugs-address=language@knowledgeroot.org'
# --copyright-holder=STRING
XGT_COPYRIGHTHOLDER='--copyright-holder=Knowledgeroot.org'



cd $KR_BASE_PATH

#echo $KR_LANGUAGE_PATH
echo "\n- Extracting languages and write to $KR_POTFILE_PATH/$KR_POTFILE \n" 

#find $KR_BASE_PATH/include/ -name '*.[pi][hn][pc]' | xgettext -f - -kT_ngettext:1,2 -kT_ --from-code=utf-8 -L PHP -F -n $XGT_MSGIDBUGSADDRESS $XGT_COPYRIGHTHOLDER -o $XGT_TEMPLATE
#`find include/ -name '*.[pi][hn][pc]' && find . -name 'index.php' -maxdepth 1` | xgettext -f - -kT_ngettext:1,2 -kT_ --from-code=utf-8 -L PHP -F -n $XGT_MSGIDBUGSADDRESS $XGT_COPYRIGHTHOLDER -o $XGT_TEMPLATE
if [ $EXTENSION_NAME = 'knowledgeroot' ]; then
  # we create language files for knowledgeroot
  find include/ -name '*.php' > /tmp/out.txt && find . -name '*.php' -maxdepth 1 >> /tmp/out.txt
  find admin/ -name '*.php' >> /tmp/out.txt
  find system/sysext/ -name '*.php' >> /tmp/out.txt
  cat /tmp/out.txt | xgettext -f - -kT_ngettext:1,2 -kT_ --from-code=utf-8 -L PHP -F -n $XGT_MSGIDBUGSADDRESS $XGT_COPYRIGHTHOLDER -o $XGT_TEMPLATE
else
  # we create language files for an extension
  find $KR_EXTENSION_PATH -name '*.php' -maxdepth 1 >> /tmp/out.txt
  cat /tmp/out.txt | xgettext -f - -kT_ngettext:1,2 -kT_ --from-code=utf-8 -L PHP -F -n $XGT_MSGIDBUGSADDRESS $XGT_COPYRIGHTHOLDER -o $XGT_TEMPLATE
fi
#find . -name 'index.php' | xgettext -j -f - -kT_ngettext:1,2 -kT_ --from-code=utf-8 -L PHP -F -n $XGT_MSGIDBUGSADDRESS $XGT_COPYRIGHTHOLDER -o $XGT_TEMPLATE
#find $KR_BASE_PATH/include/ -name '*.[pi][hn][pc]' | xgettext -f - --debug -kT_ngettext:1,2 -kT_ --from-code=utf-8 -L PHP -F -n $XGT_MSGIDBUGSADDRESS $XGT_COPYRIGHTHOLDER -o $XGT_TEMPLATE



# search in all language dirs for file $KR_POFILE
# if not exists, make a copy from original *.pot file
for f in $KR_LANGUAGE_PATH/*/LC_MESSAGES; do
  if [ ! -e $f/$KR_POFILE ]; then
    echo ">> Missing file $KR_POFILE. Create $f/$KR_POFILE"
    cp $KR_POTFILE_PATH/$KR_POTFILE $f/$KR_POFILE
  fi
done


#echo "- Merging po files with existing ones:"
#error=0
#for f in $KR_LANGUAGE_PATH/*/LC_MESSAGES; do
#  echo -n "* merging $f/$KR_POFILE: "
#  msgmerge $f/$KR_POFILE $KR_POTFILE_PATH/$KR_POTFILE --output-file=$f/$KR_POFILE.new &> /dev/null
#  if [ $? -eq 0 ]; then
#    echo "done";
#  else
#    echo "failed";
#    error=1
#  fi
#done



#echo -e "\n- Copying new po files, making backups:"
#find $KR_LANGUAGE_PATH -name $KR_POFILE | while read f; do
#  if [ -f $f ]; then
#    mv $f $f.orig
#  else
#    echo "! skipped $f because of errors during the conversation"
#    error=1
#    continue
#  fi
#  echo $f | grep -q "$KR_POFILE"
#  if [ $? -ne 1 ]; then
#    echo "* replaced $f"
#    cp $f.new $f
#  else
#    echo "* copy $f.orig to $f"
#    cp $f.orig $f
#  fi
#done


echo "\n- Clean system. \n  Erase '$KR_POFILE.new' files and /tmp/out.txt"
find $KR_LANGUAGE_PATH -type f -name $KR_POFILE'.new' -exec rm -f {} \;
if [ -e /tmp/out.txt ]; then
  #rm -f /tmp/out.txt
	echo test
fi


#echo
#if [ $error -eq 0 ]; then
#  read -p "Do you want to erase the $KR_POFILE.orig files? Default is no. (y/n)" -n1 ans
#  if [ "$ans" == "y" -o "$ans" == "Y" ]; then
#    find $KR_LANGUAGE_PATH -type f -name $KR_POFILE'.orig' -exec rm -f {} \;
#  fi
#else
#  echo -e "\n>> There were errors during the transition. Please fix!\n"
#  exit 1
#fi

echo "\n\nFinish.\n"

exit 0
