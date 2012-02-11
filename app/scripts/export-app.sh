#!/bin/bash
#
# This script exports all app directory into a zip file and then 
# unzip that archive into separate directory.
# Both (zip archive and directory) has a name containing current date.
# If an argument is given, it should be a git hash and then only 
# different files from that commit until today is exported.

NAMEBASE="wildblog-app"
TODAY=`date '+%F'`
NAMEDATE="${NAMEBASE}-${TODAY}"
NAMEZIP="${NAMEDATE}.zip"
EXPORTDIR="../exported/"

pushd .
cd ../..
echo "Cleaning ${EXPORTDIR}${NAMEDATE} and ${EXPORTDIR}${NAMEZIP} ..."
rm -rf "${EXPORTDIR}${NAMEDATE}" "${EXPORTDIR}${NAMEZIP}"
echo "Exporting app ..."
git archive --format zip --output="${EXPORTDIR}${NAMEZIP}" master app
echo "Unpacking ${EXPORTDIR}${NAMEZIP} into ${EXPORTDIR}${NAMEDATE} ..."
mkdir -p "${EXPORTDIR}${NAMEDATE}"
unzip "${EXPORTDIR}${NAMEZIP}" -d "${EXPORTDIR}${NAMEDATE}" >/dev/null
echo "app exported to ../../${EXPORTDIR}${NAMEDATE}"
if [ "x$1" != "x" ] ; then
	echo "Cleaning ${EXPORTDIR}${NAMEDATE}-$1"
	rm -rf "${EXPORTDIR}${NAMEDATE}-$1"
	echo "Stripping file list only to files changed from $1"
	for file in `git diff --name-only $1` ; do
		filedir=`dirname $file`
		mkdir -p "${EXPORTDIR}${NAMEDATE}-$1/$filedir"
		cp "${EXPORTDIR}${NAMEDATE}/$file" "${EXPORTDIR}${NAMEDATE}-$1/$file"
	done
fi
echo "==================================================="
echo "Whole tree exported to ../../${EXPORTDIR}${NAMEDATE}"
echo "Packed whole tree exported to ../../${EXPORTDIR}${NAMEZIP}"
if [ "x$1" != "x" ] ; then
	echo "Whole changed from the commit tree exported to ../../${EXPORTDIR}${NAMEDATE}-$1"
fi
echo "Done."
popd
