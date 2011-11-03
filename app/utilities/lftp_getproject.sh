#!/bin/bash
# This utility can be used to download files from ftp
#
if [ $# -lt 4 ] ; then
echo "Usage: $0 ftpusername ftppassword ftpurl ftpbasedir [subdir]"
else
if [ $# -eq 4 ] ; then 
lftp -u $1,$2 $3 <<EOF
mirror $4/project ../../project
quit
EOF
else
lftp -u $1,$2 $3 <<EOF
mirror $4/project/$5 ../../project/$5
quit
EOF
fi
fi
