<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:48
         compiled from cms.tpl */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>3D Path Designer</title>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta content="" name="description" />
	<meta content="" name="author" />

	<!-- NEED TO WORK ON -->

	<link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="assets/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link type="text/css" rel="stylesheet" href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css">
	<link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/cms.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/jquery.appendGrid-1.5.0.css" rel="stylesheet" type="text/css"/>

        <script src="assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
        <script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        
        <link href="assets/plugins/boostrap-slider/css/slider.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen"/>

</head>
<body class="">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse"> 
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="navbar-inner">
		<!-- BEGIN NAVIGATION HEADER -->
		<div class="header-seperation"> 
			<!-- BEGIN MOBILE HEADER -->
			<ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">	
				<li class="dropdown">
					<a id="main-menu-toggle" href="#main-menu" class="">
						<div class="iconset top-menu-toggle-white"></div>
					</a>
				</li>		 
			</ul>
			<!-- END MOBILE HEADER -->
			<!-- BEGIN LOGO -->	
			<a href="#">
				<img src="assets/img/logo.png" class="logo" alt="" data-src="assets/img/logo.png" data-src-retina="assets/img/logo2x.png" width="141" height="40"/>
			</a>
			<!-- END LOGO --> 
			<!-- BEGIN LOGO NAV BUTTONS -->
			<!--<ul class="nav pull-right notifcation-center">	
				<li class="dropdown" id="header_task_bar">
					<a href="#" class="dropdown-toggle active" data-toggle="">
						<div class="iconset top-home"></div>
					</a>
				</li>
				<li class="dropdown" id="header_inbox_bar">
					<a href="#" class="dropdown-toggle">
						<div class="iconset top-messages"></div>
						<span class="badge" id="msgs-badge">2</span>
						</a>
				</li> -->
				<!-- BEGIN MOBILE CHAT TOGGLER -->
				<!--<li class="dropdown" id="portrait-chat-toggler" style="display:none"> 
					<a href="#sidr" class="chat-menu-toggle">
						<div class="iconset top-chat-white"></div>
					</a>
				</li>-->
				<!-- END MOBILE CHAT TOGGLER -->				        
			<!--</ul> -->
			<!-- END LOGO NAV BUTTONS -->
		</div>
		<!-- END NAVIGATION HEADER -->
		<!-- BEGIN CONTENT HEADER -->
		<div class="header-quick-nav"> 
			<!-- BEGIN HEADER LEFT SIDE SECTION -->
			<div class="pull-left"> 
				<!-- BEGIN SLIM NAVIGATION TOGGLE -->
				<ul class="nav quick-section">
					<li class="quicklinks">
						<a href="#" class="" id="layout-condensed-toggle">
							<div class="iconset top-menu-toggle-dark"></div>
						</a>
					</li>
				</ul>
				<!-- END SLIM NAVIGATION TOGGLE -->				
				<!-- BEGIN HEADER QUICK LINKS -->
				<ul class="nav quick-section">
					<li class="quicklinks"><a href="#" class=""><div class="iconset top-reload"></div></a></li>
					<li class="quicklinks"><span class="h-seperate"></span></li>
					<li class="quicklinks"><a href="#" class=""><div class="iconset top-tiles"></div></a></li>
					<!-- BEGIN SEARCH BOX -->
					<li class="m-r-10 input-prepend inside search-form no-boarder">
						<span class="add-on"><span class="iconset top-search"></span></span>
						<input name="" type="text" class="no-boarder" placeholder="Search Dashboard" style="width:250px;">
					</li>
					<!-- END SEARCH BOX -->
				</ul>
				<!-- BEGIN HEADER QUICK LINKS -->				
			</div>
			<!-- END HEADER LEFT SIDE SECTION -->
			<!-- BEGIN HEADER RIGHT SIDE SECTION -->
			<div class="pull-right"> 
				<div class="chat-toggler">	
					<!-- BEGIN NOTIFICATION CENTER -->
					<a href="#" class="dropdown-toggle" id="my-task-list" data-placement="bottom" data-content="" data-toggle="dropdown" data-original-title="Notifications">
						<div class="user-details"> 
							<div class="username">
								&nbsp;<?php echo $this->_tpl_vars['userFirstName']; ?>
<span class="bold">&nbsp;<?php echo $this->_tpl_vars['userSurname']; ?>
</span>									
							</div>						
						</div> 
						<div class="iconset top-down-arrow"></div>
					</a>	
										<!-- END NOTIFICATION CENTER -->
					<!-- BEGIN PROFILE PICTURE -->
					<div class="profile-pic"> 
						<img src="assets/img/profiles/k.jpg" alt="" data-src="assets/img/profiles/k.jpg" data-src-retina="assets/img/profiles/k2x.jpg" width="35" height="35" /> 
					</div>  
					<!-- END PROFILE PICTURE -->     			
				</div>
				<!-- BEGIN HEADER NAV BUTTONS -->
				<ul class="nav quick-section">
					<!-- BEGIN SETTINGS -->
					<li class="quicklinks"> 
						<a data-toggle="dropdown" class="dropdown-toggle pull-right" href="#" id="user-options">						
							<div class="iconset top-settings-dark"></div> 	
						</a>
						<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="user-options">
							                                                        <li><a href="<?php echo $this->_tpl_vars['url']->createHTML('_m','Admin','_o','CMSEdit','id',$this->_tpl_vars['userId']); ?>
"><i class="fa fa-user"></i>&nbsp;&nbsp;Profile</a></li>
                                                        <?php if ($this->_tpl_vars['userRole'] == 30): ?><li><a href="<?php echo $this->_tpl_vars['url']->createHTML('_m','Admin','_o','CMSList'); ?>
"><i class="fa fa-users"></i>&nbsp;&nbsp;Admins</a></li><?php endif; ?>
							<li><a href="<?php echo $this->_tpl_vars['url']->createHTML('_m','Admin','_o','Login','logout',1); ?>
"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Logout</a></li>
						</ul>
					</li>
					<!-- END SETTINGS -->
					<li class="quicklinks"><span class="h-seperate"></span></li> 
										<!-- END CHAT SIDEBAR TOGGLE --> 
				</ul>
				<!-- END HEADER NAV BUTTONS -->
			</div>
			<!-- END HEADER RIGHT SIDE SECTION -->
		</div> 
		<!-- END CONTENT HEADER --> 
	</div>
	<!-- END TOP NAVIGATION BAR --> 
</div>
<!-- END HEADER -->

<!-- BEGIN CONTENT -->
<div class="page-container row-fluid">
	<!-- BEGIN SIDEBAR -->
	<!-- BEGIN MENU -->
	<div class="page-sidebar" id="main-menu"> 
		  <div class="page-sidebar-wrapper" id="main-menu-wrapper">
		<!-- BEGIN MINI-PROFILE -->
		<div class="user-info-wrapper">	
			<div class="profile-wrapper">
				<img src="assets/img/profiles/k.jpg" alt="" data-src="assets/img/profiles/k.jpg" data-src-retina="assets/img/profiles/k2x.jpg" width="69" height="69" />
			</div>
			<div class="user-info">
				<div class="greeting"><?php echo $this->_tpl_vars['userFirstName']; ?>
</div>
				<div class="username"><span class="semi-bold"><?php echo $this->_tpl_vars['userSurname']; ?>
</span></div>
				<div class="status">Status<a href="#"><div class="status-icon green"></div>Online</a></div>
			</div>
		</div>
		<!-- END MINI-PROFILE -->
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'common/cms_menu_left.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	</div>
	<!-- BEGIN SCROLL UP HOVER -->
	<a href="#" class="scrollup">Scroll</a>
	<!-- END SCROLL UP HOVER -->
	<!-- END MENU -->
	<!-- BEGIN SIDEBAR FOOTER WIDGET -->
		<!-- END SIDEBAR FOOTER WIDGET -->
	<!-- END SIDEBAR --> 
	<!-- BEGIN PAGE CONTAINER-->
	<div class="page-content"> 
            
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['_contentTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                
            
		
	</div>
	<!-- END PAGE CONTAINER -->
</div>
<!-- END CONTENT --> 
<!-- BEGIN CORE JS FRAMEWORK-->

<script src="assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/plugins/breakpoints.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.cookie.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js">
<!-- END CORE JS FRAMEWORK -->
<!-- BEGIN PAGE LEVEL JS -->
<script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-slider/jquery.sidr.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<!-- PAGE JS -->
<script src="assets/js/tabs_accordian.js" type="text/javascript"></script>
<!-- BEGIN CORE TEMPLATE JS -->
<script src="assets/js/core.js" type="text/javascript"></script>
<script src="assets/js/chat.js" type="text/javascript"></script> 
<script src="assets/js/demo.js" type="text/javascript"></script>
<!-- END CORE TEMPLATE JS -->

<script src="assets/js/jquery.appendGrid-1.5.0.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.json-2.4.min.js" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL JS -->

<script src="assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script> 
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<script src="assets/plugins/modernizr.js" type="text/javascript"></script>
<script src="assets/plugins/boostrap-slider/js/bootstrap-slider.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

                            <script>
                                var BaseURL = '<?php echo $this->_tpl_vars['url']->absolute(''); ?>
';
                            </script>    



<script type="text/javascript">
	//<![CDATA[
		<?php echo '
                $(document).ready(function() 
                    {
                         $("#infoBox")
                        .css( 
                        {
                           "background":"rgba(255,255,255,0.5)"
                        })
                        .dialog({ autoOpen: false, 
                                show: { effect: \'fade\', duration: 500 },
                                hide: { effect: \'fade\', duration: 500 } 
                        });

                         $("#infoButton")
                       .text("") // sets text to empty
                        .css(
                        { "z-index":"2",
                          "background":"rgba(0,0,0,0)", "opacity":"0.9", 
                          "position":"absolute", "top":"4px", "left":"4px"
                        }) // adds CSS
                    .append("<img width=\'32\' height=\'32\' src=\'assets/images/icon-info.png\'/>")
                    .button()
                        .click( 
                                function() 
                                { 
                                        $("#infoBox").dialog("open");
                                });
                    });
                    
                    
		try{
		$(document).ready(function(){
			//refresh session
			setInterval(function(){
				var x = $.ajax({ url: \''; ?>
<?php echo $this->_tpl_vars['url']->createAddress('_m','SessionRefresh','_o','CMS'); ?>
<?php echo '\'});
			},10*60*1000);
		});
		}catch(e){}
		'; ?>

	//]]>
	</script>
</body>
</html>