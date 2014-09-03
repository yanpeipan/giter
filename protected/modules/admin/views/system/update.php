<ol class="breadcrumb">
  	<li class="active">系统设置</li>
</ol>


<div class="box">
	<!-- box-content 开始-->
	<div class="box-content">
		
		<?php
		//将需要调用的东西都拉出来
		//关于服务器类型的列表		缓存类型表
		$sever_type_list = json_decode($config['SYSTEM_CACHE_TYPE_LIST'] -> cfg_value, true);
		//cdn类型表
		$cdn_type_list = json_decode($config['SYSTEM_CDN_TYPE_LIST'] -> cfg_value, true);
		//简单的is_show 只有是否两个
		$is_not = array('0' => '否', '1' => '是', );
		//写个函数处理这些

		//这个只管打印不管其它的
		function print_select_with_nothing($ids_value, $default_opition_id) {
			if (isset($ids_value[$default_opition_id])) {
				echo '<option value="' . $default_opition_id . '" selected="selected">' . $ids_value[$default_opition_id] . '</option>';
				unset($ids_value[$default_opition_id]);
			}

			foreach ($ids_value as $id => $value) {
				echo '<option value="' . $id . '">' . $value . '</option>';
			}
		}

		function print_select_with_select_id($ids_value, $default_opition_id, $select_id) {
			echo '<select id="' . $select_id . '" default_value="' . $default_opition_id . '">';
			print_select_with_nothing($ids_value, $default_opition_id);
			echo '</select>';
		}

		//未使用json格式的  数组join格式的
		//给这个专用的
		function print_select_config($ids_value, $config, $key = false) {
			$default_opition_id = $config -> cfg_value;
			$select_id = $config -> id;
			if ($key === false) {
				echo '<select setting_id="' . $select_id . '" class="input_value form-control "> default_value="' . $default_opition_id . '">';
			} else {
				echo '<select setting_id="' . $select_id . '" id="' . $key . '" class="input_value form-control " default_value="' . $default_opition_id . '">';
			}
			echo '<option value="">不使用</option>';
			print_select_with_nothing($ids_value, $default_opition_id);
			echo '</select>';
		}
		?>
			
			
			
		<table class="tuzi_cms_video_info">
		
		    <tr>
		        <td width="8%">授权码：</td>
		        <td width="25%">
		        	<input type="text" value="<?php echo $config['SYSTEM_TOKEN_KEY'] -> cfg_value;?>" default_value="<?php echo $config['SYSTEM_TOKEN_KEY'] -> cfg_value;?>" class="input_value form-control " setting_id="<?php echo $config['SYSTEM_TOKEN_KEY'] -> id;?>">
		       	</td>
		    </tr> 
		       
		   	<tr>
		        <td width="8%">域名:</td>
		        <td width="25%">

		        	<input type="text" value="<?php echo $config['SYSTEM_DOMAIN'] -> cfg_value;?>" default_value="<?php echo $config['SYSTEM_DOMAIN'] -> cfg_value;?>"  class="input_value form-control  form-control" setting_id="<?php echo $config['SYSTEM_DOMAIN'] -> id;?>" onchange="must_be_url(this)"> *必须以http开头
		        </td>
		    </tr>    
		    <tr>
		        <td width="8%">BOSS系统域名:</td>
		        <td width="25%">

		        	<input type="text" value="<?php echo $config['SYSTEM_BOSS_DOMAIN'] -> cfg_value;?>" default_value="<?php echo $config['SYSTEM_BOSS_DOMAIN'] -> cfg_value;?>"  class="input_value form-control  form-control" setting_id="<?php echo $config['SYSTEM_BOSS_DOMAIN'] -> id;?>" onchange="must_be_url(this)"> *必须以http开头
		        </td>
		    </tr>    
		   	<tr>
		        <td width="8%">网站名称：</td>
		        <td width="25%">
		        	<?php //echo $config['SYSTEM_WEBSITE_NAME']->cfg_value;?>
		        	<input type="text" value="<?php echo $config['SYSTEM_WEBSITE_NAME'] -> cfg_value;?>" default_value="<?php echo $config['SYSTEM_WEBSITE_NAME'] -> cfg_value;?>"  class="input_value form-control " setting_id="<?php echo $config['SYSTEM_WEBSITE_NAME'] -> id;?>" >

		        </td>

		    </tr>
			
		   	<tr>   
		        <td width="8%">缓存服务器类型:</td>
		        <td width="25%">
		        	
					<?php
					$type_list = explode(',', $config['SYSTEM_CACHE_TYPE_LIST'] -> cfg_value);
					$ids_value = array();
					foreach ($type_list as $value) {
						$ids_value[$value] = $value;
					}
					//print_select_config($ids_value, $config['SYSTEM_CACHE'], 'SYSTEM_CACHE');
					?>
		        </td>
		    </tr>    
		   	<tr>
		        <td width="8%">缓存服务器：</td>
		        				
		        <td width="25%">
		        			<?php
							$cache_sever_count = count(json_decode($config['SYSTEM_CACHE_SEVER'] -> cfg_value, true));

							if ($cache_sever_count > 0) {
								echo ' <a href="#"  class="btn-primary btn show_list_button" onclick="show_sever_list()">查看缓存服务详情</a>';
							} else {
								echo '无';
							}
		        			?>
				</td>
		    </tr>
		    
		   
			<?php
			foreach (json_decode($config['SYSTEM_CACHE_SEVER']->cfg_value,true) as $key => $sever) {
				echo '<tr class="sever_row"><td width="8%" class="td_ip"></td><td>IP:<input type="text" value="' . $sever['ip'] . '" class="server_ip form-control ">　PORT:<input type="text" value="' . $sever['port'] . '" class="server_port form-control "></td></tr>';
			}
			?>
			<input type="hidden" value=<?php echo $config['SYSTEM_CACHE_SEVER'] -> cfg_value;?> id="SYSTEM_CACHE_SEVER" setting_id="<?php echo $config['SYSTEM_CACHE_SEVER'] -> id;?>">
			<tr id="last_row" class="sever_row"><td width="8%" class="td_ip"></td><td>IP:<input type="text" value="" class="server_ip form-control ">　PORT:<input type="text" value="" class="server_port form-control "></td></tr>
			<tr class="sever_row" ><td width="8%" class="td_ip"></td><td><a href="#" class="btn-primary btn " id="btn_show_sever_last_row" onclick="show_sever_last_row(this)">添加</a>  <a href="#" class="btn-primary btn" onclick="show_sever_list()">确定</a></td></tr>
					
					
		     
		   	<tr>   
		        <td width="8%">CDN服务商:</td>
		        <td width="25%">
		        	<?php
					$type_list = explode(',', $config['SYSTEM_CDN_TYPE_LIST'] -> cfg_value);
					$ids_value = array();
					foreach ($type_list as $value) {
						$ids_value[$value] = $value;
					}

					//print_select_config($ids_value, $config['SYSTEM_CDN'], 'SYSTEM_CDN');
					?>
		        </td>
		     </tr>    
		   
		    
		    <?php
		    	//针对可能会使用多个不同cdn配置切换 (同时使用数量为1)
		    	foreach ($config as $key => $value) {
					if(strstr($key,'SYSTEM_CDN_CONFIG_')){
						
						$cdn_sever_name=strtolower(str_replace('SYSTEM_CDN_CONFIG_', '', $key));
						//$cdn_config=json_decode($config['SYSTEM_CDN_CONFIG_UPYUN']->cfg_value,true);
						$cdn_config=json_decode($value->cfg_value,true);
						$maybe_not_exist=array('uri','user','psw','host');
						foreach ($maybe_not_exist as $key_must_be_set) {
							if(!isset($cdn_config[$key_must_be_set])){
								$cdn_config[$key_must_be_set]='';
							}
						}
			?>
			
			<input type="hidden" class="input_value" value=<?php echo $value -> cfg_value;?> default_value=<?php echo $value -> cfg_value;?> id="<?php echo $key;?>" setting_id="<?php echo $value -> id;?>">
			
			<tr class="cdn_row <?php echo $cdn_sever_name;?>"><td>CDN空间地址</td><td><input type="text" value="<?php echo $cdn_config['host'];?>" data_id="<?php echo $key;?>" cdn_opt="host" class="cdn_opt form-control "></td></tr>
			<tr class="cdn_row <?php echo $cdn_sever_name;?>"><td>CDN登陆名称</td><td><input type="text" value="<?php echo $cdn_config['user'];?>" data_id="<?php echo $key;?>" cdn_opt="user" class="cdn_opt form-control "></td></tr>
			<tr class="cdn_row <?php echo $cdn_sever_name;?>"><td>CDN空间密码</td><td><input type="text" value="<?php echo $cdn_config['psw'];?>" data_id="<?php echo $key;?>" cdn_opt="psw" class="cdn_opt form-control "></td></tr>
			<tr class="cdn_row <?php echo $cdn_sever_name;?>"><td>CDN访问地址</td><td><input type="text" value="<?php echo $cdn_config['uri'];?>" data_id="<?php echo $key;?>" cdn_opt="uri" class="cdn_opt form-control " onchange="must_be_url(this)">  *必须以http开头 </td></tr>
			
		    <?php
					}
				}
		    ?>
		    <tr>
		        <td width="100%"  id="tr_config_submit"></td><td><a href="#" id="submit_config" class="btn btn-primary " onclick="config_submit()">更改配置</a></td>
		    </tr>
		</table>
	
		
		
		
		
	</div>
	
	<!-- box-content 结束-->
</div>

<script>
	function show_sever_list() {
		if($('.server_ip').length < 3) {
			$(".sever_row").toggle();
			$('#last_row').hide();
			$('#btn_show_sever_last_row').show();
		} else if($('.server_ip').length == 3) {
			$(".sever_row").toggle();

			$('#btn_show_sever_last_row').hide();
		} else {
			$(".sever_row").toggle();

			$('#btn_show_sever_last_row').hide();
			$('#last_row').remove();
		}
	}

	function show_cdn_list() {
		
		$(".cdn_row").toggle();
		
		$('.'+$('#SYSTEM_CDN').val()).show();
	}

	function show_sever_last_row(o) {
		$('#last_row').show();
		$('#btn_show_sever_last_row').hide();
	}

	function list_last_row_change() {
		$('#last_row').change(function() {
			if($('.server_ip').length < 3) {
				var tmp = $(this).clone(true);
				$(this).unbind();
				$(this).removeAttr('id');

				$(this).after(tmp);
				$('#last_row input').val('');
			} else {
				$('#btn_show_sever_last_row').hide();
			}

		});
	}

	function config_submit() {

		var opts_changed = new Array();

		//先把各项取出来,进行改动验证

		//单项配置的数据
		$('.input_value').each(function() {
			var input_value = $(this).val();
			var default_value = $(this).attr('default_value');
			//alert(input);
			if(input_value != default_value) {
				var opt_changed = new Object();
				opt_changed.id = $(this).attr('setting_id');
				opt_changed.input_value = input_value;
				//	alert(input_value);
				opts_changed.push(opt_changed);
			}
		});
		//缓存服务器的配置
		var system_cache_sever_opts = new Array();
		$('.sever_row').each(function() {
			var ip = $(this).children("td").children('.server_ip').val();
			var port = $(this).children("td").children('.server_port').val();
			if(!(ip && port)) {

			} else {
				var server_config = new Object();
				server_config.ip = ip;
				server_config.port = port;
				system_cache_sever_opts.push(server_config);
			}

		});
		var SYSTEM_CACHE_SEVER = JSON.stringify(system_cache_sever_opts);

		var SYSTEM_CACHE_SEVER_DEFAULT = $('#SYSTEM_CACHE_SEVER').val();
		if(SYSTEM_CACHE_SEVER_DEFAULT != SYSTEM_CACHE_SEVER) {
			//alert('服务器设置不同'+SYSTEM_CACHE_SEVER+' '+SYSTEM_CACHE_SEVER_DEFAULT);
			var opt_changed = new Object();
			opt_changed.id = $('#SYSTEM_CACHE_SEVER').attr('setting_id');
			opt_changed.input_value = system_cache_sever_opts;
			opts_changed.push(opt_changed);
		}

		


		//alert(SYSTEM_CACHE_SEVER);

		if(opts_changed.length != 0) {
			var json_string_opts_changed = JSON.stringify(opts_changed);
		//	alert(json_string_opts_changed);
		//	return false;
			$.get("/admin/system/update", {
				'system_config' : json_string_opts_changed
			}, function(data) {
				if(data.status == 1) {
					alert(data.info);
					window.location.reload();
				} else {
					alert(data.info);
				}
			}, "json");
		} else {
			alert('没有任何配置改动');
		}

	}

	//网址必须是http开头
	function must_be_url(o) {
		//$(o).val();
		if(isUrl($(o).val())) {
			$('#submit_config').show();
			$(o).css('background', '#FFF');
		} else {
			$('#submit_config').hide();
			$(o).css('background', '#F00');
		}
	}

	function isUrl(urlString) {
		var regExp = /http:\/\//i;
		if(urlString.match(regExp)) {
			return true;
		} else {
			return false;
		}
	}
	
	//
	function listenCdnChange(){
		$('#SYSTEM_CDN').change(function(){
			if($(this).val()){
				$(".cdn_row").hide();				
				$('.'+$('#SYSTEM_CDN').val()).show();
			}else{
				$(".cdn_row").hide();
			}
		});
		
		$('#SYSTEM_CDN').mousedown(function(){
			if($(this).val()){
				$(".cdn_row").hide();				
				$('.'+$('#SYSTEM_CDN').val()).show();
			}else{
				$(".cdn_row").hide();
			}
		});
	}
	
	function listenCdnConfigChange(){
		//多个cdn配置  直接只记录到隐藏域
		$('.cdn_opt').change(function(){
			var opt=$(this).val();
			var data_id=$(this).attr('data_id');
			var tmp_string=$('#'+$(this).attr('data_id')).val();
			var tmp_obj=eval('(' + tmp_string + ')');
			var cdn_opt=$(this).attr('cdn_opt');
			eval('tmp_obj.'+cdn_opt+'="'+opt+'";');			
			var tmp_string=JSON.stringify(tmp_obj);			
			$('#'+data_id).val(tmp_string);
		});
	}
	$(document).ready(function() {
		//alert('dd');
		list_last_row_change();
		listenCdnChange();
		listenCdnConfigChange();

	});

</script>
<style type="text/css">
	#server_list {
		display: none;
		width: 800px;
		position: fixed;
		left: 50%;
		margin-left: -400px;
		top: 10%;
		background: #FFF;
		padding: 30px;
	}
	#close_button {
		margin-top: -30px;
		margin-right: -20px;
	}
	#server_list_conent {
		margin-top: 400px;
		height: auto;
		margin: 0 auto;
	}
	#last_row {
		display: none;
	}
	#cdn_list {
		display: none;
		width: 800px;
		position: fixed;
		left: 50%;
		margin-left: -400px;
		top: 10%;
		background: #FFF;
		padding: 30px;
	}
	#close_cdn_button {
		margin-top: -30px;
		margin-right: -20px;
	}
	#cdn_list_conent {
		margin-top: 400px;
		height: auto;
		margin: 0 auto;
	}
	#tr_config_submit {
		text-align: center
	}
	.cdn_row, .sever_row {
		background: #36a9e1;
		display: none;
	}
	.cdn_row, .sever_row {
		background: #DDD;
		display: none;
	}
	.td_ip {
		border: none;
	}
	input {
		width: 300px;
		height: 25px;
	}
	#.show_list_button {
		background: #DDD;
		color: #000;
	}
	.form-control {
		width: 300px;
		display: inline
	}

	
</style>