#!/bin/bash
#
sed -ri 's/(.*)time=[0-9]+(.*)/\1\2/' $1
sed -ri 's/\[.*\]//g' $1
uniq $1 > $1.temp
