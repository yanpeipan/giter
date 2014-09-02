#!/bin/sh
domain=<?php echo $domain;?>

repositoriesRoot=<?php echo $root_path;?>

apache2=<?php echo $apache_bin;?>

if [ ! -d ${repositoriesRoot} ];then
    mkdir  ${repositoriesRoot} || error_exit "Error:cannot create  directory "${repositoriesRoot}
fi
dir=${repositoriesRoot}"/"${domain}
cd ${repositoriesRoot} ||  error_exit "Cannot change directory"
git init --bare ${domain} && cd ${dir} && git update-server-info
cp ${dir}/hooks/post-update.sample  ${dir}/hooks/post-update
chown -R www-data. ${dir}
${apache2} restart