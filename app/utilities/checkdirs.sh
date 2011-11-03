#!/bin/bash

# check directory structure and file attributes

BASE="../../"

if [ $# -lt 1 ] ; then
echo "Usage: $0 project"
else

writable_app_dirs=(
"app/themes/Common/images/ico"
)
	
writable_project_dirs=(
"cache/controllers" 
"cache/models" 
"cache/templates_c" 
"images" 
"files"
"log"
"backup" 
)

writable_project_files=(
"config/version"
"config/config.php"
)

	
file_name="$BASE$1"
if [ -d $file_name ]
then
	echo "chmod -R 0755 $file_name"
	chmod -R 0755 $file_name
else
	echo "mkdir -p -m 0755 $file_name"
	mkdir -p -m 0755 $file_name
fi

	
for file in ${writable_app_dirs[@]}; do
	file_name="$BASE$file"
	if [ -d $file_name ]
	then
		echo "chmod -R 0777 $file_name"
		chmod -R 0777 $file_name
	else
		echo "mkdir -p -m 0777 $file_name"
		mkdir -p -m 0777 $file_name
	fi
done


for file in ${writable_project_dirs[@]}; do
	file_name="$BASE$1/$file"
	if [ -d $file_name ]
	then
		echo "chmod -R 0777 $file_name"
		chmod -R 0777 $file_name
	else
		echo "mkdir -p -m 0777 $file_name"
		mkdir -p -m 0777 $file_name
	fi
done


for file in ${writable_project_files[@]}; do
	file_name="$BASE$1/$file"
	if [ -f $file_name ]
	then
		echo "chmod 0666 $file_name"
		chmod 0666 $file_name
	else
		echo "echo \"\" > $file_name"
		echo "" > $file_name
		echo "chmod 0666 $file_name"
		chmod 0666 $file_name
	fi
done

fi

