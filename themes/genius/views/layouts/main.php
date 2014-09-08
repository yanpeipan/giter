<!DOCTYPE html>
<html lang="en">
<head>
  <!--[if !IE]>-->

      <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery-2.1.0.min.js"></script>

  <!--<![endif]-->

  <!-- start: Meta -->
  <meta charset="utf-8">
  <title><?php echo Yii::app()->name;?></title>
  <meta name="description" content="VO">
  <meta name="author" content="Tuzi Team">
  <meta name="keyword" content="VO">
  <!-- end: Meta -->

  <!-- start: Mobile Specific -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- end: Mobile Specific -->

  <!-- start: CSS -->
  <link href="<?php echo Yii::app()->theme->baseUrl;?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo Yii::app()->theme->baseUrl;?>/assets/css/style.min.css" rel="stylesheet">
  <link href="<?php echo Yii::app()->theme->baseUrl;?>/assets/css/main.css" rel="stylesheet">
  <link href="<?php echo Yii::app()->theme->baseUrl;?>/assets/css/retina.min.css" rel="stylesheet">
  <link href="<?php echo Yii::app()->theme->baseUrl;?>/assets/css/print.css" rel="stylesheet" type="text/css" media="print"/>
  <!-- end: CSS -->


  <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>

      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/respond.min.js"></script>

  <![endif]-->

  <!-- start: Favicon and Touch Icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo Yii::app()->theme->baseUrl;?>/assets/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo Yii::app()->theme->baseUrl;?>/assets/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo Yii::app()->theme->baseUrl;?>/assets/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo Yii::app()->theme->baseUrl;?>/assets/ico/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl;?>/assets/ico/favicon.png">
  <!-- end: Favicon and Touch Icons -->	


  <!-- start: Jquery -->


  <!--[if IE]>

    <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery-1.11.0.min.js"></script>

  <![endif]-->

  <!--[if !IE]>-->

<script type="text/javascript">
window.jQuery || document.write("<script src='<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery-2.1.0.min.js'>"+"<"+"/script>");
</script>

  <!--<![endif]-->

  <!--[if IE]>

<script type="text/javascript">
window.jQuery || document.write("<script src='<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery-1.11.0.min.js'>"+"<"+"/script>");
</script>

  <![endif]-->
  <!-- end: jquery -->

</head>
<body>
<?php if(!Yii::app()->user->isGuest):?>
  <!-- start: Header -->
  <header class="navbar">
    <div class="container">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".sidebar-nav.nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
      </button>
      <a id="main-menu-toggle" class="hidden-xs open"><i class="fa fa-bars"></i></a>		
      <a class="navbar-brand col-md-2 col-sm-1 col-xs-2" href="<?php echo Yii::app()->createUrl('/');?>">
        <span style="margin-left: 35px;"><?php echo Yii::app()->name;?></span>
      </a>
      <div id="search" class="col-sm-4 col-xs-8 col-lg-3" style="display:none;">
        <select>
          <option>everything</option>
          <option>messages</option>
          <option>comments</option>
          <option>users</option>
          </select>
        <input type="text" placeholder="search" />
        <i class="fa fa-search"></i>
      </div>
      <!-- start: Header Menu -->
      <div class="nav-no-collapse header-nav">
        <ul class="nav navbar-nav pull-right">

          <!-- start: User Dropdown -->
          <li class="dropdown">
            <a class="btn account dropdown-toggle" data-toggle="dropdown" href="#">
              <div class="avatar"><img src="<?php echo Yii::app()->theme->baseUrl;?>/assets/img/avatar.jpg" alt="Avatar"></div>
              <div class="user">
                <span class="hello">欢迎！</span>
                <span class="name"><?php echo Yii::app()->user->name;?></span>
              </div>
            </a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo Yii::app()->createUrl('admin/user/changepassword');?>"><i class="fa fa-user"></i> 修改密码</a></li>
              <li><a href="<?php echo Yii::app()->createUrl('admin/user/logout');?>"><i class="fa fa-off"></i> 退出</a></li>
            </ul>
          </li>
          <!-- end: User Dropdown -->
        </ul>
      </div>
      <!-- end: Header Menu -->

    </div>	
  </header>
  <!-- end: Header -->
<?php endif;?>

  <?php echo $content;?>


<?php if(!Yii::app()->user->isGuest):?>
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Modal title</h4>
        </div>
        <div class="modal-body">
          <p>Here settings can be configured...</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <div class="clearfix"></div>

  <footer>

    <div class="row">

      <div class="col-sm-5">
        &copy; 2014 Luxtone. 
      </div><!--/.col-->

      <div class="col-sm-7 text-right">
        <a href="http://tuziv.tv"> Virtual Operation System </a> by Tuzi Team
      </div><!--/.col-->	

    </div><!--/.row-->	

  </footer>
<?php endif;?>
  <!-- start: JavaScript-->

  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery-migrate-1.2.1.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/bootstrap.min.js"></script>




  <!-- page scripts -->
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.ui.touch-punch.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.sparkline.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/fullcalendar.min.js"></script>
  <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/excanvas.min.js"></script><![endif]-->
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.autosize.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.placeholder.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/moment.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/daterangepicker.min.js"></script>

  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.easy-pie-chart.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.chosen.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.noty.min.js"></script>
  <!-- theme scripts -->
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/custom.min.js"></script>
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/core.min.js"></script>

  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/jquery.jeditable.min.js"></script>
  <!-- inline scripts related to this page -->
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/echarts/els.js"></script>
  <!-- end: JavaScript-->

  <!---start : vo JavaScript-->
  <script src="<?php echo Yii::app()->theme->baseUrl;?>/assets/js/public.js"></script>	
  <!--end : vo JavaScript-->
</body>
</html>
