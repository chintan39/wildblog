#!/bin/bash

if ! ls controllers routes models>/dev/null 2>&1 ; then
	echo "This script has to be run inside package directory (cd app/packages/somepackage)."
	exit 1
fi


