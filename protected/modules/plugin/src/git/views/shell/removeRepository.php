#!/bin/sh
apache_config_file=<?php echo $apache_config_file; ?>

repositoriesRoot=<?php echo $repositoriesRoot;?>

domain=<?php echo $domain;?>

rm -rf ${apache_config_file} || exit_error "Error:"${apache_config_file}" is not  writable"
cd ${repositoriesRoot} && rm -rf  ${domain} || exit_error "Error:rm "${repositoriesRoot}${domain}" failed"