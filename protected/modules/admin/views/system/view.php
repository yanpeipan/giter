<ol class="breadcrumb">
  	<li class="active">基本设置</li>
</ol>
<div class="box">
	<!-- box-content 开始-->
	<div class="box-content">
		
		<?php
			//将需要调用的东西都拉出来
			//关于服务器类型的列表
			
			$sever_type_list=json_decode($config['SYSTEM_CACHE_TYPE_LIST'],true);
			
			
			$cdn_type_list=json_decode($config['SYSTEM_CDN_TYPE_LIST'],true);
			
		?>
			
			
			
		<table class="tuzi_cms_video_info">
		
		    <tr>
		        <td width="8%">授权码：</td>
		        <td width="25%">
		        	<?php echo $config['SYSTEM_TOKEN_KEY']; ?>
		       	</td>
		        
		        
		        <td width="8%">域名:</td>
		        <td width="25%"><?php echo $config['SYSTEM_DOMAIN']; ?></td>
		        <td width="8%">网站名称：</td>
		        <td width="25%"><?php echo $config['SYSTEM_WEBSITE_NAME']; ?></td>
		    </tr>
			<tr>
		        <td width="8%">是否缓存：</td>
		        <td width="25%">
		        	<?php 
		        		if($config['SYSTEM_IS_CACHE']){
		        			echo '是';
		        		}else{
		        			echo '否';
		        		}
		        	?>
		       	</td>
		        
		        
		        <td width="8%">缓存类型:</td>
		        <td width="25%"><?php echo $sever_type_list[$config['SYSTEM_CACHE_TYPE']]; ?></td>
		        <td width="8%">缓存服务器数量：</td>
		        				
		        <td width="25%">
		        			<?php 
		        				$cache_sever_count=count(json_decode($config['SYSTEM_CACHE_SEVER'],true));
								
								if($cache_sever_count>0){
									echo '共计:'.$cache_sever_count.'台 <a href="#" onclick="show_sever_list()">查看详情</a>'; 
								}else{
									echo '无';
								}
		        				
		        			?>
				</td>
		    </tr>
		    
		    <tr>
		        <td width="8%">是否cdn：</td>
		        <td width="25%">
		        	<?php 
		        		if($config['SYSTEM_IS_CDN']){
		        			echo '是';
		        		}else{
		        			echo '否';
		        		}
		        	?>
		       	</td>
		        
		        
		        <td width="8%">CDN服务商:</td>
		        <td width="25%"><?php echo $cdn_type_list[$config['SYSTEM_CDN_TYPE']]; ?></td>
		        
		        <td width="8%"></td>		        				
		        <td width="25%">
		        			<?php //echo count(json_decode($config['SYSTEM_CACHE_SEVER'],true)); ?>
				</td>
		    </tr>
		    
			 
		</table>
	
		
		
		<table class="tuzi_cms_video_info" id="server_list">	
			<tr><td>缓存服务器地址</td><td>端口</td></tr>		
			<?php
				foreach (json_decode($config['SYSTEM_CACHE_SEVER'],true) as $key => $sever) {
					echo '<tr><td>'.$sever['ip'].'</td><td>'.$sever['port'].'</td></tr>';
				}
				$sever_type_list=json_decode($config['SYSTEM_CACHE_TYPE_LIST'],true);
				
				$cdn_type_list=json_decode($config['SYSTEM_CDN_TYPE_LIST'],true);
				
			?>
			
			
		</table>
	</div>
	
	<!-- box-content 结束-->
</div>
<script>
	function show_sever_list(){
	
		$("#server_list").slideToggle(1000);
	}
	
	
</script>
<style type="text/css">
	#server_list{display:none;}
</style>