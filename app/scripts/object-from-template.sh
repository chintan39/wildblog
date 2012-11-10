#!/bin/bash

# This is a helper for creating controller/model/router objects

usage() {
	echo "Usage: `basename $0` create-package | create-classes [ClassesName]"
	exit 1
}

createpackage() {
	# this function creates base structure for package and main package object
	# name of the package is retrived from actual directory name

	if [ "$(basename `readlink -f ..`)" != "packages" ] || ls controllers routes models>/dev/null 2>&1 ; then
		echo "This command has to be run inside empty package directory."
		exit 1
	fi

	PACKAGE=`basename $(pwd)`
	PACKAGE_UPPER=`echo $PACKAGE | tr '[a-z]' '[A-Z]'`

	echo "Creating directory structure for package '$PACKAGE' ..."
	
	mkdir models controllers routes

	sed -e "s/PACKAGENAME/$PACKAGE_UPPER/" \
	    -e "s/PackageName/$PACKAGE/" \
	    <../templates/PackageNamePackage.php >"${PACKAGE}Package.php"

	echo "Please see ${PACKAGE}Package.php and fill in all values in __value__ format or enhance code otherwise."
	echo "Done."
}

createclasses() {
	# this function creates trio of model/controller/routes objects
        # name of the package is retrived from actual directory name

	if ! ls controllers routes models>/dev/null 2>&1 ; then
		echo "This command has to be run inside package directory (cd app/packages/somepackage)."
		echo "You may want to run `basename $0` create-package."
		exit 1
	fi

	if [ $# -lt 1 ] ; then
		echo "Object name has to be specified for create-classes"
		usage
	fi

        PACKAGE=`basename $(pwd)`
        PACKAGE_UPPER=`echo $PACKAGE | tr '[a-z]' '[A-Z]'`
	OBJECT=$1
        OBJECT_UPPER=`echo $OBJECT | tr '[a-z]' '[A-Z]'`
        OBJECT_LOWER=`echo $OBJECT | tr '[A-Z]' '[a-z]'`

	echo "Creating controller/model/routes objects for object $OBJECT in package $PACKAGE ..."

	sed -e "s/PACKAGENAME/$PACKAGE_UPPER/" \
            -e "s/PackageName/$PACKAGE/" \
            -e "s/OBJECTNAME/$OBJECT_UPPER/" \
            -e "s/ObjectName/$OBJECT/" \
            -e "s/objectname/$OBJECT_LOWER/" \
            <"../templates/controllers/PackageNameObjectNameController.php" >"controllers/${PACKAGE}${OBJECT}Controller.php"

	sed -e "s/PACKAGENAME/$PACKAGE_UPPER/" \
            -e "s/PackageName/$PACKAGE/" \
            -e "s/OBJECTNAME/$OBJECT_UPPER/" \
            -e "s/ObjectName/$OBJECT/" \
            -e "s/objectname/$OBJECT_LOWER/" \
            <"../templates/models/PackageNameObjectNameModel.php" >"models/${PACKAGE}${OBJECT}Model.php"

	sed -e "s/PACKAGENAME/$PACKAGE_UPPER/" \
            -e "s/PackageName/$PACKAGE/" \
            -e "s/OBJECTNAME/$OBJECT_UPPER/" \
            -e "s/ObjectName/$OBJECT/" \
            -e "s/objectname/$OBJECT_LOWER/" \
            <"../templates/routes/PackageNameObjectNameRoutes.php" >"routes/${PACKAGE}${OBJECT}Routes.php"

        echo "Please see controllers/${PACKAGE}${OBJECT}Controller.php,"
	echo "models/${PACKAGE}${OBJECT}Model.php routes/${PACKAGE}${OBJECT}Routes.php"
	echo "and fill in all values in __value__ format or enhance code otherwise."

        echo "Done."

}

if [ $# -lt 1 ] ; then
	usage
fi

if [ "$1" == "create-package" ] ; then
	createpackage
	exit 0
fi

if [ "$1" == "create-classes" ] ; then
        createclasses $2
	exit 0
fi

usage
exit 1
