<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
unset($data_path);
include("includes/define.php");
if(LISTERRSITE == "on") error_reporting(0);
$simple_error = new Simple_Error();
$CMS = new CMS();
$simple_db_work = new Simple_DbApi();
$simple_error->Test_File_Insite(DATA_PATH."/config.php",SIMPLE_NOT_FILE." config.php");
$simple_error->Test_File_Insite(DATA_PATH."/system.php",SIMPLE_NOT_FILE." system.php");
$simple_error->Test_File_Insite(INCLUDES."/lib.php",SIMPLE_NOT_FILE." lib.php");
$simple_error->Test_File_Insite(LANGUAGES."/".LANDSITE.".php",SIMPLE_NOT_FILE." ".LANGUAGES."/".LANDSITE.".php");
$CMS->closed_site();
if(!isset($_SESSION["login_user"])) $_SESSION["status_user"] = 0;
$simple_db_work->connect_db();
$CMS->test_ip_ban();

(int)$status = $_GET['status'];

switch ($status)
{
	case 1:
		if($CMS->TestValidMail($_POST['id']) == false) echo 1;
		else
		{
			$testMail = $simple_db_work->CountTable('registration','mail',strtolower($_POST['id']));
			if($testMail > 0) echo 2;
			else echo 3;
		}
		break;
	case 2:
		if(isset($_POST['name']) && isset($_POST['mail']) && isset($_POST['phone']) && isset($_POST['type']) && isset($_POST['comment']))
		{
			$uslug=array(1=>'Ремонт и отделка',2=>'Ремонт компьютеров и ноутбуков',3=>'Салон красоты на выезд',4=>'Сертификация и регистрация товарного знака',5=>'Установка и ремонт бытовой техники',6=>'Фотоуслуги , дизайн. оформление',7=>'Частный сектор',8=>'Мастер на час',9=>'Грузчики и грузоперевозки');
			$name = strip_tags($_POST['name']);
			$mail = strip_tags($_POST['mail']);
			$phone = strip_tags($_POST['phone']);
			$type = $uslug[$_POST['type']];
			$message = strip_tags($_POST['comment']);

			if($name != '' && $mail != '' && $phone != '')
			{
				include('class.phpmailer.php');
				$fromMail = 'no-reply@korika-service.ru';
				$subject = 'Заказ услуги';
				$html = '<p>Имя: <b>'.$name.'</b></p>';
				$html .= '<p>E-mail: <b>'.$mail.'</b></p>';
				$html .= '<p>Телефон: <b>'.$phone.'</b></p>';
				$html .= '<p>Услуга: <b>'.$type.'</b></p>';
				$html .= '<p>Сообщение: <br /><b>'.$message.'</b></p>';
				
				$toMail = 'mail@korika-service.ru';
				
				$mail12 = new PHPMailer();
				$mail12->setFrom($fromMail);
				$mail12->addAddress($toMail);
				$mail12->Subject = $subject;
				$mail12->isHTML();
				$mail12->CharSet = 'UTF-8';
				$mail12->Body = $html;
				$mail12->send();
			}
		}
		break;
	case 3:
		if(isset($_POST['name']) && isset($_POST['mail']) && isset($_POST['phone']) && isset($_POST['comment']))
		{
			
			$name = strip_tags($_POST['name']);
			$mail = strip_tags($_POST['mail']);
			$phone = strip_tags($_POST['phone']);
			$message = strip_tags($_POST['comment']);

			if($name != '' && $mail != '' && $phone != '')
			{
				include('class.phpmailer.php');
				$fromMail = 'no-reply@korika-service.ru';
				$subject = 'Обратная связь';
				$html = '<p>Имя: <b>'.$name.'</b></p>';
				$html .= '<p>E-mail: <b>'.$mail.'</b></p>';
				$html .= '<p>Телефон: <b>'.$phone.'</b></p>';
				$html .= '<p>Сообщение: <br /><b>'.$message.'</b></p>';
				
				$toMail = 'mail@korika-service.ru';
				
				$mail12 = new PHPMailer();
				$mail12->setFrom($fromMail);
				$mail12->addAddress($toMail);
				$mail12->Subject = $subject;
				$mail12->isHTML();
				$mail12->CharSet = 'UTF-8';
				$mail12->Body = $html;
				$mail12->send();
			}
		}
		break;
	default:
		if(file_exists(DATA_PATH.'modules/registration/config.php')) include(DATA_PATH.'modules/registration/config.php');
		if($CMS->TestData($LoginUser,$_POST['id']) == false) echo 1;
		else
		{
			$testLogin = $simple_db_work->CountTable('registration','login',strtolower($_POST['id']));
			if($testLogin > 0) echo 2;
			else echo 3;
		}
		break;
}

?>