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
echo 111111111111111111;
(int)$status = $_GET['status'];

$toMail = 'tomail@mail.ru';

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
		foreach($_POST as $v) $v = htmlspecialchars($v);
		$name = $_POST['name'];
		$mail = $_POST['mail'];
		$phone = $_POST['phone'];
		$question = $_POST['question'];
		$timetocall1=$_POST['time-1'];
		$timetocall2=$_POST['time-2'];
		$date1=$_POST['date-1'];
		$date2=$_POST['date-2'];
		
		if($phone != '')
		{
			include('class.phpmailer.php');
			$fromMail = 'no-reply@klincy-avto.ru';
			$subject = $_POST['subject'];
			$html ='';
			if($name!='') $html = '<p>Имя: <b>'.$name.'</b></p>';
			if($mail!='') $html .= '<p>E-mail: <b>'.$mail.'</b></p>';
			if($phone!='') $html .= '<p>Телефон: <b>'.$phone.'</b></p>';
			if($question!='') $html .= '<p>Вопрос: <b>'.$question.'</b></p>';
			if($timetocall1!='' || $timetocall2!='') $html .= '<p>Время звонка: <b> с '.$timetocall1.' до '.$timetocall2.'</b></p>';
			if($date1!='' || $date2!='') $html .= '<p>Дата бронирования: <b> с '.$date1.' до '.$date2.'</b></p>';
			
			
			
			$mail1 = new PHPMailer();
			$mail1->setFrom($fromMail);
			$mail1->addAddress($toMail);
			$mail1->Subject = $subject;
			$mail1->isHTML();
			$mail1->CharSet = 'UTF-8';
			$mail1->Body = $html;
			$mail1->send();
			print_r($html);
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