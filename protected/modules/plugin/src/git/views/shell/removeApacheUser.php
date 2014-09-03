#!/bin/sh
group_name=<?php echo $group_name; ?>

username=<?php echo $username; ?>

apache_group_file=<?php echo $apache_group_file; ?>

if [ -f ${apache_group_file} -a -w ${apache_group_file} ];then
	sed -i \"/^${group_name}:/s/ ${username} / /g\" ${apache_group_file}
else
	error_exit "Error:"${group_name}" is not writable"
fi