server
{
    listen 80;
    server_name  <?php echo $server_name; ?>;
    root <?php echo $root; ?>/;
    access_log  <?php echo $access_log;?>  access;

    location ~ .*\.(php|php5)?$
    {
        try_files $uri =404;
        fastcgi_pass  <?php echo $fastcgi_pass;?>;
        fastcgi_index <?php echo $index;?>;
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
            rewrite (.*) /<?php echo $index;?>;
        }
        index <?php echo $index;?>;
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