<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Главная | <?=TITLESITE?></title>
        <meta name="keywords" content="<?=$Pro100KeyWords?>" />
		<meta name="description" content="<?=$Pro100Description?>" />
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<meta name="viewport" content="width=device-width">
		
		<?=$CMS->insert_css_file("reset.css");?>
		<?=$CMS->insert_css_file("animate.css");?>
		<?=$CMS->insert_css_file("fonts/font.css");?>
		<?=$CMS->insert_css_file("slick/slick.css");?>
		<?=$CMS->insert_css_file("style.css");?>
		
		<?=$simple_js_lib->InsertJs()?>
	</head>
	<body class="mainbg">
<div class="allsite">	
	
	<div class="overlayer"></div>
	<div id="erroralert" class="erroralert"></div>
	<div class="popup popup-video">
		<i class="close"></i>
	</div>

	<div class="test"></div>
	<div class="mobmenu"></div>
	<a href="/" class="moblogo"></a>
	<div class="mobmenuinner">
		<div class="mobmenuli">
			<?=$center?>
		</div>
	</div>

	<!-- top -->
	<div class="top">
		<div class="topinner">
			<a href="/" class="mainlogo"></a>
			<div class="topmenu">
				<?=$center?>
				<ul class="subtopmenu"></ul>
			</div>
		</div>
	</div>
	<!-- content -->
	<div class="content">
		<a href="https://www.youtube.com/embed/ct92ny5JMYQ?autoplay=1&rel=0&enablejsapi=1" class="video_button showvideo">
		</a>
		<div class="maintext">
			<p>Мы делаем<br>
<span class="mt_notbold">самые</span> <span class="mt_blue">яркие</span><br>
фестивали<br>
В СТРАНЕ</p>
			<a href="/userfiles/Prezentatsia_Vmeste_Zazhigaem.pdf" class="button mainbutton" download>Скачать презентацию</a>
		</div>
	</div>
	
</div>	
	<script src="<?=URL_SITE_THEME?>js/common.js?<?=rand(1,10000)?>"></script>
	<script src="<?=URL_SITE_THEME?>js/slick/slick.js"></script>

