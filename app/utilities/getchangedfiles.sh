#!/bin/bash
# This utility can be used to copy changed files into separate directories 
# with directory structure kept.

DEST_DIR="../app_changed/"

if [ $# -lt 1 ] ; then
echo "Usage: $0 revision"
else

cd ..
rm -rf $DEST_DIR
mkdir $DEST_DIR

echo "Retrieving svn info..."

for file in `svn diff -r $1:HEAD --summarize | cut -d " " -f 8`; do
	FILE_PARENT=`dirname $file`
	mkdir -p "$DEST_DIR$FILE_PARENT"
	cp $file "$DEST_DIR$file"
	echo "Created $DEST_DIR$file"
done

cd utilities

echo "Done."

fi


