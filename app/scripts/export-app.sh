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
TIMESTAMPFILE="app/export-${TODAY}"

pushd .
cd ../..
echo "Project root is '`pwd`'"
echo "Current branch:\n`git branch`"
echo "Folder ${EXPORTDIR}${NAMEDATE} and ${EXPORTDIR}${NAMEZIP} will be cleaned"
read -p "Is the information above correct? (y/n): "
if [ "x$REPLY" != "xy" -a "x$REPLY" != "xY" ] ; then popd ; exit 1 ; fi
rm -rf "${EXPORTDIR}${NAMEDATE}" "${EXPORTDIR}${NAMEZIP}"
echo "Exporting app ..."
git archive --format zip --output="${EXPORTDIR}${NAMEZIP}" HEAD
echo "Unpacking ${EXPORTDIR}${NAMEZIP} into ${EXPORTDIR}${NAMEDATE} ..."
mkdir -p "${EXPORTDIR}${NAMEDATE}"
unzip "${EXPORTDIR}${NAMEZIP}" -d "${EXPORTDIR}${NAMEDATE}" >/dev/null
read -p "Remove mpdf? (y/n): "
if [ "x$REPLY" == "xy" -o "x$REPLY" == "xY" ] ; then rm -rf "${EXPORTDIR}${NAMEDATE}/app/libs/mpdf" ; fi
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
touch "${EXPORTDIR}${NAMEDATE}/${TIMESTAMPFILE=}"
echo "==================================================="
echo "Whole tree exported to ../../${EXPORTDIR}${NAMEDATE}"
echo "Packed whole tree exported to ../../${EXPORTDIR}${NAMEZIP}"
if [ "x$1" != "x" ] ; then
	echo "Whole changed from the commit tree exported to ../../${EXPORTDIR}${NAMEDATE}-$1"
fi
echo "Done."
popd
