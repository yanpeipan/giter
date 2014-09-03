server
{
    listen 80;
    server_name  <?php echo $domain, $url_host; ?>;
    root <?php echo $htdocs_path, $domain; ?>/;
    access_log  /var/web-logs/<?php echo $domain, '.', $url_host; ?>-access.log  access;

    location ~ .*\.(php|php5)?$
    {
        try_files $uri =404;
        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME $document_root/$fastcgi_script_name;
    }

    location / {
        if (-f $request_filename/index.html){
            rewrite (.*) $1/index.html break;
        }
        if (-f $request_filename/index.php){
            rewrite (.*) $1/index.php;
        }
        if (!-f $request_filename){
            rewrite (.*) /index.php;
        }
        index index.php;
    }

    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
        expires      30d;
    }

    location ~ .*\.(js|css)?$
    {
        expires      12h;
    }
}