#!/bin/bash
# This utility can be used to upload changed files to ftp
#
TMP_FILE="/tmp/wildblog_upload_changed"
if [ $# -lt 4 ] ; then
echo "Usage: $0 ftpusername ftppassword ftpurl ftpbasedir"
else
cd ..
echo "" > $TMP_FILE
TMP_FILES=`svn status | cut -d " " -f 8`
for file in $TMP_FILES ; do 
	echo "PUT $file -o $4/app/$file" >>$TMP_FILE 
done
echo "quit" >>$TMP_FILE
#cat $TMP_FILE
lftp -u $1,$2 $3 <$TMP_FILE
fi

