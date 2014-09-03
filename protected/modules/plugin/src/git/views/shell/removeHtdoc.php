#!/bin/sh
htdocs=<?php echo $htdocs;?>

domain=<?php echo $domain;?>

if [ -d ${htdocs} -a -x ${htdocs} ];then
	cd ${htcods}
	if [ -d ${domain} -a -w {$domain} ];then
		rm -rf ${domain} 
	else
		error_exit "Error:"${htdocs}${domain}" is not exists or writable"
	fi
else
	error_exit "Error:"${htdocs}" is not exists or executable"
fi