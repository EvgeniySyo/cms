<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link rel="stylesheet" type="text/css" href="../templates/admin/css/dialog.css" />
<link href="../templates/admin/css/style.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="../templates/admin/js_admin/w.js"></script>
<script type="text/javascript" src="../templates/admin/js_admin/jquery-1.4.4.min.js"></script>

<script type="text/javascript" src="../templates/admin/js_admin/ajaxupload.3.5.js"></script>
<script type="text/javascript" src="../templates/admin/js_admin/dialog_box.js"></script>
<script type="text/javascript" src="../templates/admin/js_admin/fancybox/jquery.mousewheel-3.0.4.pack.js"></script> 
<script type="text/javascript" src="../templates/admin/js_admin/fancybox/jquery.fancybox-1.3.4.pack.js"></script> 
<link rel="stylesheet" type="text/css" href="../templates/admin/js_admin/fancybox/jquery.fancybox-1.3.4.css" media="screen" /> 
<script type="text/javascript" src="../templates/admin/js_admin/easyTooltip.js"></script>
<script type="text/javascript" src="../templates/admin/js_admin/ui.js"></script>
<script type="text/javascript">  
jQuery(document).ready(function(){
 		jQuery("a.fancy").fancybox();
 		jQuery("a").easyTooltip();
 		jQuery("img").easyTooltip();
 		jQuery('ul.tree li:last-child').addClass('last');
        jQuery(".alertWindow").draggable();
         jQuery(".editWindow").draggable();
        tableLine();
 
       function tableLine() {
	   jQuery('.tablelist tr').not('.head').removeClass('odd').removeClass('int');
	   jQuery('.tablelist tr:odd').not('.head').addClass('odd');
	   jQuery('.tablelist tr:even').not('.head').addClass('int');
	     //else $(this).removeClass('odd').addClass('int');
  }
 });
</script>
<style type="text/css">
#loader {display:block; z-index: 600}
</style>
<script type="text/javascript">
function hideLoaderPro100() {
	document.getElementById('loader').style.display = 'none';
	document.getElementById('FullBg').style.display = 'none';
	
}
</script>
<title>Панель администратора</title> 
</head> 
<body>
	<div id="FullBg"></div>
<div id="loader">
    <div class="loader-indicator">
    <img src="../templates/admin/large-loading.gif" alt="" />Simple CMS<br />
    <span id="loader-msg">Идет загрузка...</span>
    </div>
</div>

<div class="all" id="AdminPanel"> 
<div class="TopLineL"><span class="p">Sim<span class="n">ple</span> cms</span></div> 
<div class="TopLineR">версия <?=$enginering?></div> 
<div class="TopLine"></div> 
<div class="MenuLine"> 
<div class="exit"><a href="admin.pl?exit">выход</a></div> 
<div class="MainBlockS">
<ul class="mains"> 
        <li><a href="site.pl">САЙТ</a></li>
        <li><a href="module.pl">КОМПОНЕНТЫ</a></li>        
        <li><a href="component.pl">КОНТЕНТ</a></li>         
        <li><a href="block.pl">БЛОКИ</a></li>
        <li><a href="filemanager.pl">ФАЙЛОВЫЙ МЕНЕДЖЕР</a></li>             
      </ul> 
</div> 
</div> 
<div class="clear"></div> 
 <div class="center"> 
<!-- content --> 
<?php include(DATA_PATH."/admin/content.php"); ?>
<!-- end --> 
</div> 
<div class="lineBottom"></div> 

</div> 
<div class="bottom">
<div class="BottomLines">Simple CMS <?=date('Y')?></div>
</div>
<script type="text/javascript">
if (window.addEventListener) {
	window.addEventListener('load', hideLoaderPro100, false);
} else if (window.attachEvent) {
	var r = window.attachEvent("onload", hideLoaderPro100);
} else {
	hideLoaderPro100();
}
</script>
</body> 
</html> 