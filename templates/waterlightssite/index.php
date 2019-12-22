<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?=$title;?> | <?=TITLESITE?></title>
        <meta name="keywords" content="<?=$Pro100KeyWords?>" />
		<meta name="description" content="<?=$Pro100Description?>" />
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
		<meta name="viewport" content="width=device-width">
		
		<?=CMS::insert_css_file("reset.css");?>
		<?=CMS::insert_css_file("fonts/font.css");?>
		<?=CMS::insert_css_file("slick/slick.css");?>
		<?=CMS::insert_css_file("style.css");?>
		<?=$simple_js_lib->InsertJs()?>
		<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	</head>
	<body>
<div class="allsite">	
	<div class="popup popup2 formdata">
		<i class="close"></i>
		<div class="pop2content">
		<div class="popup-title formtitle"><h2>Благодарим за заявку</h2></div>
		<p>Мы свяжемся с вами в ближайшее время</p>
		</div>
	</div>
	
	<div class="popup popup-all formdata">
		<i class="close"></i>
		<div class="popup-title formtitle"><h2></h2></div>
		<div class="popup-inner">
			<input type="text" name="name" placeholder="Как вас зовут?" class="backinput">
			<input type="text" name="mailorphone" placeholder="Ваш e-mail или телефон" class="backinput">
			<textarea name="quest" placeholder="Сообщение" class="backtextarea"></textarea>
			<input type="button" value="Отправить" class="sendformback" data-title="">
			<div class="fz152">Нажимая кнопку отправить я принимаю условия пользовательского соглашения и даю согласие на обработку персональных данных в соответствии с требованиями <a href="http://www.kremlin.ru/acts/bank/24154">152-ФЗ</a></div>
		</div>
		
	</div>
	
	<div class="overlayer"></div>
	<div id="erroralert" class="erroralert"></div>

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
			<a href="/" class="logo"></a>
			<div class="topmenu">
				<?=$center?>
				<ul class="subtopmenu"></ul>
			</div>
		</div>
	</div>
	<!-- content -->
	<div class="content">
		<?php echo $module;?>
	</div>
	
</div>
<?php echo $down;?>
<!-- bottom -->
<div class="bottom">
	<div class="bottominner">
		<div class="bottommail">e-mail: <a href="mailto:info@vmeza.ru">info@vmeza.ru</a></div>
		<div class="bottomsocials"><ul>
			<li><a href="https://vk.com/vmeste_zajigaem" target="_blank"><img src="<?=URL_SITE_THEME?>images/ico_vk.png" alt=""></a></li>
			<!--<li><a href="https://www.youtube.com/user/TheVmesteZajigaem" target="_blank"><img src="<?=URL_SITE_THEME?>images/ico_youtube.png" alt=""></a></li>-->
			<li><a href="https://www.instagram.com/vmeste_zajigaem/" target="_blank"><img src="<?=URL_SITE_THEME?>images/ico_instagram.png" alt=""></a></li>
		</ul></div>
		<div class="bottomtext"><span>© 2011-<?php echo date('Y'); ?> ООО «Вмеза».</span> Все права защищены.</div>
	</div>
</div>	
	<script src="<?=URL_SITE_THEME?>js/common.js"></script>
	<script src="<?=URL_SITE_THEME?>js/slick/slick.js"></script>

