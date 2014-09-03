#!/bin/sh
htpasswd=<?php echo $htpasswd_bin; ?>

git=<?php echo $apache_user_file; ?>

usr=<?php echo $usr; ?>

psw=<?php echo $psw; ?>

apache=<?php echo $server->apache_bin; ?>

if [ -f  ${git} -a -w ${git} ];then
    ${htpasswd} -b ${git} ${usr} ${psw}
else
    ${htpasswd} -c ${git} ${usr} ${psw}
fi
if [ -x ${apache} ];then
    error_exit "Error:"${apache}" not executable"
else
    ${apache} restart
fi