#!/bin/sh
nginx=<?php echo $ngixn_bin;?>

if [ -x ${nginx} ];then
	${nginx} -s reload
else
	exit_error "Error:"${nginx}" is not executable"
fi