#!/bin/sh
group_name=<?php echo $group_name; ?>

apache_group_file=<?php echo $apache_group_file;?>

if [ -f ${apache_group_file} -a -s ${apache_group_file} ];then
	sed -i \"/^${group_name}:/d\" ${apache_group_file}"
elif [ ! -w ${apache_group_file} ];then
	error_exit "Error:"${apache_group_file}" is not writable"
fi