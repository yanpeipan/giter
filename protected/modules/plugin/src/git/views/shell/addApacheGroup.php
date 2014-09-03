#!/bin/sh
group_file=<?php echo $apache_group_file;?>

group_name=<?php echo $group_name;?>

if [ -f ${group_file} -a -s ${group_file} -a -w ${group_file} ];then
	sed -i "$ a\\${group_name}: admin " ${group_file}
else
	echo "${group_name}: admin "> ${group_file} || error_exit "Error:"${$group_file}" is not writable "
fi