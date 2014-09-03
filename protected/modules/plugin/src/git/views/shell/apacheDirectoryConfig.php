<Directory "<?php echo $root_path;?><?php echo $domain;?>.git/">
 	Allow from all
        	Order Allow,Deny
        	<Limit GET PUT POST DELETE PROPPATCH MKCOL COPY MOVE LOCK UNLOCK>
        		Require group <?php echo $domain;?>
        	</Limit>
</Directory> 