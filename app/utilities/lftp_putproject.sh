#!/bin/bash
# This utility can be used to upload files to ftp
#
if [ $# -lt 4 ] ; then
echo "Usage: $0 ftpusername ftppassword ftpurl ftpbasedir [subdir]"
else
cd ../..
if [ $# -eq 4 ] ; then 
lftp -u $1,$2 $3 <<EOF
mirror -R project $4/project
quit
EOF
else
lftp -u $1,$2 $3 <<EOF
mirror -R project/$5 $4/project/$5
quit
EOF
fi
fi
