#!/bin/sh
apache_config_file=<?php echo $apache_config_file; ?>

repositoriesRoot=<?php echo $repositoriesRoot;?>

domain=<?php echo $domain;?>

rm -rf ${apache_config_file} || error_exit "Error:"${apache_config_file}" is not  writable"
cd ${repositoriesRoot} && rm -rf  ${domain} || error_exit "Error:rm "${repositoriesRoot}${domain}" failed"