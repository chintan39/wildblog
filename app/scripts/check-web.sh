#!/bin/bash

if [ $# -ne 1 ] ; then
	echo "Usage: $0 [web url]" && exit 1
fi

web=$1
message=""

# just ping the server first
ping -c 1 $web >/dev/null 2>&1 || message="${message} Ping $web didn't succeeded." 

# try to get html and find errors
wget -O - >/dev/null 2>&1 || message="${message} Get Contents didn't succeeded."

if [ -z "$message" ] ; then
	notify-send -u low "Web $web is going well."
else
	logger -t webcheck "Web $web has the following problems: ${message}"
	notify-send -u critical "Web $web has the following problems: ${message}"
fi

