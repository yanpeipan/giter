<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang=en lang=en>
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8"/>
<?php 
   Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/web-main.css');
   Yii::app()->clientScript->registerCoreScript('jquery');
   Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/slides.js');
?>
<title>tuzi.tv</title>
<!--[if IE 6]>
<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/js/DD_belatedPNG.js');?>
<script type="text/javascript">       
    DD_belatedPNG.fix('logo,img,.home,.about,.luntan,.bangzhu,.header,.login_type_text,.index_logn .youx_img,.index_logn .password_img,.index_logn .login_btn,.index_logn .reg_btn,.index_logn .sryoux_img,.index_logn .sz_password_img,.index_logn .ok_btn,.index_logn .ok_login_btn,.pic_bj .pic_main .pic_dian span,.pic_bj .xiazai_pic a,.footer,');
</script>
<![endif]-->
<link rel = "Shortcut Icon" href='/images/favicon.ico'>
</head>  
<body>
    <div id="wrap">
        <div class="top" style="display:none">
            <div class="w962 index_logn">
               <div>
                   <form action="/site/login" method="POST" id="login" class="form" style="display:none">
                       <span class="youx_img lf p7"></span>
                       <span class="login_type_text lf"><input type="text" placeholder="" class="login_text_bj"></span>
                       <span class="password_img lf p7"></span>
                       <span class="login_type_text lf"><input type="password" placeholder="" class="login_text_bj"></span>
                       <input type="button" class="login_btn">
                       <input type="button" class="reg_btn">
                   </form>
               </div>
               <div>
                   <form action="/site/register" method="POST" id="register" class="form" >
                       <span class="sryoux_img lf p7"></span>
                       <span class="login_type_text lf"><input id="re_email" type="text" placeholder="" class="login_text_bj" name=User[email]></span>
                       <span class="sz_password_img lf p7"></span>
                       <span class="login_type_text lf"><input id="re_pwd" type="password" placeholder="" class="login_text_bj" name=User[pwd]></span>
                       <input type="button" class="ok_btn">
                       <input type="button" class="ok_login_btn">
                   </form>
               </div>
            </div>
        </div>
       <?php echo $content;?>
       <div class="footer">
          <div class="w962 footer_bq">
            <img src="/images/yejiao.png" width="275px" height="20px"> 
          </div>
       </div>
    </div>
<script type="text/javascript">
$(document).ready(function(){
        $("#slides").slides({
            width: 965,
            height: 570,
            navigation:false,
            playInterval:5000,
            startAtSlide:1,
            pauseInterval:8000
        });
        setTimeout(function(){$("#slides").slides("play");},5000);
    /*
    //注册
    $('.ok_btn').bind('click',function(){
        var email = $('#re_email').val();
        var pwd   = $('#re_pwd').val();
        var data  = {'email':email,'pwd':pwd}
        $.ajax({
            'url':'/site/register',
            'type':'POST',
            'data':data,
            'success':function(json){
                alert(json.error_pwd);
            }
        });
    });

    $('#switch').bind('click',function(){
        $('.top').toggle('slow');
        $(this).attr({'src':'/images/arrow2.png'});
        return false;
    });
    $('#top').slides({
        preload: true,
        generateNextPrev: true
    });
    */

});
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30942975-1']);
  _gaq.push(['_setDomainName', 'tuzi.tv']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</body>
</html>







