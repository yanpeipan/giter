#!/bin/bash
#first install epel yum source
rpm -ivh http://www.niubilety.com/file/rpm/epel-release-6-8.noarch.rpm
#second install git server needed soft
yum install git-core gitweb nginx spawn-fcgi fcgi fcgi-devel pcre pcre-devel
#third add a www-data user
useradd -s /sbin/nologin www-data
#compile install nginx-1.6.3
wget http://nginx.org/download/nginx-1.6.3.tar.gz
if [[ -f nginx-1.6.3.tar.gz ]]; then
	tar zxf nginx-1.6.3.tar.gz && cd nginx-1.6.3
	./configure --prefix=/etc/nginx --sbin-path=/usr/sbin/nginx --conf-path=/etc/nginx/nginx.conf --error-log-path=/var/log/nginx/error.log --http-log-path=/var/log/nginx/access.log --pid-path=/var/run/nginx.pid --lock-path=/var/run/nginx.lock --http-client-body-temp-path=/var/cache/nginx/client_temp --http-proxy-temp-path=/var/cache/nginx/proxy_temp --http-fastcgi-temp-path=/var/cache/nginx/fastcgi_temp --http-uwsgi-temp-path=/var/cache/nginx/uwsgi_temp --http-scgi-temp-path=/var/cache/nginx/scgi_temp --user=nginx --group=nginx --with-http_ssl_module --with-http_realip_module --with-http_addition_module --with-http_sub_module --with-http_dav_module --with-http_flv_module --with-http_mp4_module --with-http_gzip_static_module --with-http_random_index_module --with-http_secure_link_module --with-http_stub_status_module --with-mail --with-mail_ssl_module --with-file-aio --with-ipv6 --with-http_auth_request_module --with-cc-opt='-O2 -g -pipe -Wall -Wp,-D_FORTIFY_SOURCE=2 -fexceptions -fstack-protector --param=ssp-buffer-size=4 -m64 -mtune=generic'
	if [[ $? = 0 ]]; then
		make && make install
	else
		echo "configure error"
	fi
else
	echo "nginx-1.6.3.tar.gz not exist"
fi
#install fcgiwrap
wget  http://github.com/gnosek/fcgiwrap/tarball/master -O fcgiwrap.tar.gz
if [[ -f fcgiwrap.tar.gz ]]; then
	#statements
	tar zxf fcgiwrap.tar.gz && cd gnosek-fcgiwrap* && autoreconf && ./configure && make && make install || echo "install fcgiwrap fail"
else
	echo "SOCKET=/var/run/fcgiwrap.socket" >> /etc/sysconfig/spawn-fcgi
	echo 'OPTIONS="-u www-data -g www-data -s $SOCKET -S -M 0600 -C 32 -F 1 -P /var/run/spawn-fcgi.pid -- /usr/local/sbin/fcgiwrap"' >> /etc/sysconfig/spawn-fcgi
	echo "install fcgiwrap sucess"
fi
#config nginx
cd /etc/nginx && sed -i '/user/s/nginx/www-data/' nginx.conf && cd conf.d && rename .conf .conf.bak *.conf
wget http://www.niubilety.com/file/nginx/git.server.conf
if [[ -f git.server.conf ]]; then
	#statements
	echo "nginx config complete and after 3 second start gitweb server"
	sleep 3
	/etc/init.d/spawn-fcgi start
	/etc/init.d/nginx start
else
	echo "git nginx web config file not exist"
fi
