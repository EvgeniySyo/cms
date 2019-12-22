<?php

function send_mail($str_mail, $str_name, $str_phonemail, $str_message,$subject)
{
	$headers  = "Content-type: text/html; charset=utf-8 \r\n";
	$headers .= "From: ".$str_email."\r\n";
	$subject1 = $subject." Сообщение от: ".$str_name;
	$headers .= "Subject: ".$subject1."\r\n";
	$msg="
		<html>
		<body>
		Имя: $str_name<br/>
		E-MAIL ИЛИ ТЕЛЕФОН: $str_phonemail<br/>
		Сообщение: $str_message <br/>
		</body>
		</html>
		";
	$additional = "-f ".$str_email."\r\n";
	mail($str_mail, $subject1, $msg, $headers,$additional);
}

if (isset($_POST['send']))
{
	$name    = strip_tags($_POST['name']);
	$phonemail   = strip_tags($_POST['phonemail']);
	$message = strip_tags($_POST['message']);
	$code    = strip_tags($_POST['code']);

	$send = true;

	if($capcha == 1)
	{
		if (!isset($_SESSION['captcha']) || $_SESSION['captcha'] != $code) {$send = false;$content .= CMS::section_list(1,"%TEXT%","Введён неправильный код с картинки.");}
	}

	if($send == true)
	{
		if ($name!='' && $message!='' && $phonemail!='')
		{
			send_mail($mail, $name, $phonemail, $message,$subject);
			$content .= CMS::section_list(1,"%TEXT%","Ваше сообщение отправлено.");
		}
		else
		{
			if(!$name) $content .= CMS::section_list(1,"%TEXT%","Поле \"имя\" не заполнено.");
			if(!$message) $content .= CMS::section_list(1,"%TEXT%","Поле \"сообщение\" не заполнено.");
			if(!$phonemail) $content .= CMS::section_list(1,"%TEXT%","Введён неправильный адрес электронной почты.");
		}
	}
}
if (empty($content)) {
    $content = '';
}
$before = file_get_contents(DATA_PATH."modules/".$_GET['page']."/before.txt");
//if ($before!='') $content .= CMS::section_list(2,"%TEXT%",$before);

if($capcha == 1) $infocapcha = CMS::section_list(5,'','');
$content .= CMS::section_list(3,"{capcha},{before}",$infocapcha.'<><>'.$before);

$after = file_get_contents(DATA_PATH."modules/".$_GET['page']."/after.txt");
if ($after!='') $content .= CMS::section_list(4,"%TEXT%",$after);
?>