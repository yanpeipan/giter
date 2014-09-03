#!/bin/sh
nginx=<?php echo $ngixn_bin;?>

if [ -x ${nginx} ];then
	${nginx} -s reload
else
	error_exit "Error:"${nginx}" is not executable"
fi