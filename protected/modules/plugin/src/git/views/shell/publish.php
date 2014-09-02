#!/bin/sh
project_dir=<?php echo $project_dir; ?>

htdocs=<?php echo $htdocs; ?>

origin=<?php echo $origin; ?>

if [ -d ${project_dir} ];then
	cd ${project_dir}  ||  error_exit "Error:Cannot change directory"${project_dir}
	git pull
else
	cd ${htdocs} ||  error_exit "Error:Cannot change directory"${htdocs}
	git clone ${origin}
fi