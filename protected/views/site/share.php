<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>兔子分享</title>
<link rel="stylesheet" href="/webroot/style/share.css" type="text/css" />
<script src='/webroot/js/jquery-1.4.js'></script>
<script>


$(document).ready(function(){
	//�Զ��л�����
	var atuokey=true;
	//��ǰλ��
    var current_pos=0;
	$('.left_img').mouseover(function(){atuokey = false;});
	$('.left_img').mouseout(function(){atuokey = true;});
	$('.right_img').mouseover(function(){atuokey = false;});
	$('.right_img').mouseout(function(){atuokey = true;});
	$('.main_img').mouseover(function(){atuokey = false;});
	$('.main_img').mouseout(function(){atuokey = true;});
	$('.small_tu').mouseover(function(){atuokey = false;});
	$('.small_tu').mouseout(function(){atuokey = true;});


	$('.right_img').click(function(){
		current_pos == 2 ? current_pos=0 : current_pos++;
		if(current_pos>0){
			$('.left_img').show();
		}
		if(current_pos==2){
			$('.right_img').hide();
		}
		
		$('.main_img div').not('#app'+current_pos).hide();
		$('#app'+current_pos).fadeIn('slow');
		$('.small_tu div').not('#pic'+current_pos).hide();
		$('#pic'+current_pos).fadeIn('slow');
	});
	$('.left_img').click(function(){
		current_pos == 0 ? current_pos=2 : current_pos--;
		if(current_pos==0){
			$('.left_img').hide();
		}
		if(current_pos<2){
			$('.right_img').show();
		}
		
		$('.main_img div').not('#app'+current_pos).hide();
		$('#app'+current_pos).fadeIn('slow');
		$('.small_tu div').not('#pic'+current_pos).hide();
		$('#pic'+current_pos).fadeIn('slow');
	});
	var autoFocus=function(){
		if(!atuokey){return false;}
		
		current_pos == 2 ? current_pos=0 : current_pos++;
		
		$('.main_img div').not('#app'+current_pos).hide();
		$('#app'+current_pos).fadeIn('slow');
		$('.small_tu div').not('#pic'+current_pos).hide();
		$('#pic'+current_pos).fadeIn('slow');
		
		if(current_pos>0 && current_pos<2){
			$('.right_img').show();
			$('.left_img').show();

		}
		if(current_pos>=2){
			$('.left_img').show();
			$('.right_img').hide();

		}
		if(current_pos<=0){
			$('.left_img').hide();
			$('.right_img').show();
		}
		
		
	}
	setInterval(autoFocus,5000);
});

 
</script>
</head>

<body>
	<div class="con_top">
			<img src="/webroot/style/images/logo.png" />
    </div>
	<div class="content ">
		
		<div class="con_img fix">
			<img src="/webroot/style/images/1.png" />
			<img src="/webroot/style/images/2.png" />
			<img src="/webroot/style/images/3.png" />
		</div>
		<h1 class="title_p">将iPhone手机、iPAD、iPOD、Android手机、平板电脑和计算机等移动设备的内容传输到高清电视播放，与家人好友共同分享</h1>
        <p class="intro center">“兔子分享”能通过无线网络将所有移动设备的内容传输到安装了“兔子视频HD”的高清智能电视和智能电视播放器上，包括您手机上的视频、录像、音乐和照片，未来还将支持手机屏幕投影和多屏互动。</p>
		<div class="line_img">
			<img src="/webroot/style/images/xian.png" />
		</div>
		<h1 class="M10">无线传输，用更大的屏幕和更好的音质与家人好友分享生活。</h1>
		<div class="active_con M10">
			<div class="left_img" style="display:none"><a href="javascript:#Pre"></a></div>
			<div class="middle_main">
					<div class="main_img">
						<div id='app0'><img src="/webroot/style/images/appletv2.png" /></div>
						<div id='app1' style="display:none"><img src="/webroot/style/images/appletv1.png" /></div>
						<div id='app2' style="display:none"><img src="/webroot/style/images/appletv.png" /></div>
					</div>
					<div class="small_tu">
						<div id='pic0'><img src="/webroot/style/images/pic1.png" /></div>
						<div id='pic1' style="display:none"><img src="/webroot/style/images/pic.png" /></div>
						<div id='pic2' style="display:none"><img src="/webroot/style/images/pic2.png" /></div>
					</div>
						
				<div class="opacity_img">	
				</div>
				<div class="thtft_img"><img src="/webroot/style/images/cp_9.png" /></div>
			</div>
			<div class="right_img"><a href="javascript:#Next"></a></div>
		</div>
		<div class="line_img2">
			<img src="/webroot/style/images/xian.png" />
		</div>
		<div class="border_line">
			<h1 class="text_left">围坐在电视前的久违感觉</h1>
            <p class="w_title">家是温馨的港湾，围坐在电视旁欣赏电影大片、聆听天籁之音、浏览自己的旅途和孩子的成长。<br />
               在茶余饭后，在假日里，把小孩子带到客厅，把自己和亲友带到客厅来，补偿一下平常少之又少的交流机会，分享生活的感悟、关怀和爱。</p>
		</div>
		<div class="more">
            <h1 class="more_style">了解更多<br />
            支持兔子分享的产品</h1>
            <div class="show_img fix">
                <ul>
                    <li><img src="/webroot/style/images/cp_1.png" />
                    <p>灵锐3</p>
                    </li>
                    <li><img src="/webroot/style/images/cp_2.png" />
                    <p>iPhone4</p>
                    </li>
                    <li><img src="/webroot/style/images/cp_3.png" />
                    <p>iPOD4 </p>
                    </li>
                    <li><img src="/webroot/style/images/cp_4.png" />
                    <p style="padding-left:12px;">Android Phone</p>
                    </li>
                    <li><img src="/webroot/style/images/5.png" />
                    <p>Android PAD   </p>

                    </li>
                  <!--  <li class="P16"><img src="/webroot/style/images/cp_6.png" />
                    <p style="padding-left:12px;">同方x46笔记本 </p>
                    </li>-->
                    <li style="padding-left:15px;"><img src="/webroot/style/images/cp_7.png" />
                    <p>长虹智能电视</p>

                    </li>
                </ul>
            </div>
		</div>
	</div>
	<div class="footer">
                <p>
                   <a href="http://tuziv.com/about" target="_blank">关于我们</a>
                   <a href="http://e.weibo.com/tuzivideo" target="_blank">新浪微博</a>
                   <a href="http://t.qq.com/tuzi-tv?preview" target="_blank">腾讯微博</a>
                   
                   <a href="javascript:setFavorite();" style="border:0">加入收藏</a>
                  
                   </p>
                <p>(C) 2012 兔子视频 京ICP备12009161号-2</p>
    </div>
</body>
</html>
<script>
    function setFavorite(){
        sURL = 'http://tuziv.com';
        sTitle = '兔子分享';
        if(document.all){
            window.external.AddFavorite(sURL, sTitle);
        }else{
            window.sidebar.addPanel(sTitle, sURL, "");
        }
    }
</script>
