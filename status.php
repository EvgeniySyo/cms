<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
unset($data_path);
include("includes/define.php");
if(LISTERRSITE == "on") error_reporting(0);
$simple_db_work = new Simple_DbApi();
CMS::$error->Test_File_Insite(DATA_PATH."/config.php",SIMPLE_NOT_FILE." config.php");
CMS::$error->Test_File_Insite(DATA_PATH."/system.php",SIMPLE_NOT_FILE." system.php");
CMS::$error->Test_File_Insite(INCLUDES."/lib.php",SIMPLE_NOT_FILE." lib.php");
CMS::$error->Test_File_Insite(LANGUAGES."/".LANDSITE.".php",SIMPLE_NOT_FILE." ".LANGUAGES."/".LANDSITE.".php");
CMS::closed_site();
if(!isset($_SESSION["login_user"])) $_SESSION["status_user"] = 0;
CMS::test_ip_ban();
(int)$status = $_GET['status'];
if(isset($_GET['page']) && $_GET['page']!='')$GLOBALS['section']=CMS::read_file_in_theme();
CMS::CoreComponent('user');
CMS::CoreComponent('json');

$toMail = 'tomail@mail.ru';
if(file_exists(DATA_PATH."/modules/back/config.php"))
{
	include(DATA_PATH."/modules/back/config.php");
	$toMail=$mail;
	$subject=$subject;
}
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
	case 'back':
		foreach($_POST as $v) $v = htmlspecialchars($v);
		$name = $_POST['name'];
		$phone = $_POST['mailorphone'];
		$comment = $_POST['quest'];
		
		
		if($phone != '')
		{
			//$toMail='info@galaktika32.ru';
			include('class.phpmailer.php');
			$fromMail = 'no-reply@'.$_SERVER['HTTP_HOST'];
			if(isset($_POST['subject'])) $subject = $_POST['subject'];
			$html ='';
			if($name!='') $html = '<p>Имя: <b>'.$name.'</b></p>';
			if($phone!='') $html .= '<p>E-mail или телефон: <b>'.$phone.'</b></p>';
			if($comment!='') $html .= '<p>Сообщение: <b>'.$comment.'</b></p>';
			
			$mail1 = new PHPMailer();
			$mail1->setFrom($fromMail);
			$mail1->addAddress($toMail);
			$mail1->Subject = $subject;
			$mail1->isHTML();
			$mail1->CharSet = 'UTF-8';
			$mail1->Body = $html;
			$mail1->send();
			echo 1;
		}
		break;
		case 3:
			if(is_numeric($_POST['id']))
			{
				$selectimgs = CMS::$db->query("select * from `delivery` where `id`='".(int)$_POST['id']."' ");
				if(!empty($selectimgs))
				{
					$dd=current($selectimgs);
					$group='';
					if($dd['href']!='') $group='<div class="popupvk"><a href="'.$dd['href'].'" target="_blank"><img src="/userfiles/vk.png"></a></div>';
					echo '<div class="popup-title formtitle"><h2>'.$dd['name'].'</h2></div>
		'.$dd['desc'].$group;
				}
			}
		break;
		case 4:
			$toMail='info@galaktika32.ru';
			include('class.phpmailer.php');
			$fromMail = 'no-reply@galaktika32.ru';
			$subject = 'Обратная связь';
			$html ='';
			$html = '<p>Имя: <b>емец</b></p>';
			$html .= '<p>E-mail: <b>emece@inbox.ru</b></p>';
			$html .= '<p>Телефон: <b>89605576659</b></p>';
			
			
			
			
			$mail1 = new PHPMailer();
			$mail1->setFrom($fromMail);
			$mail1->addAddress($toMail);
			$mail1->Subject = $subject;
			$mail1->isHTML();
			$mail1->CharSet = 'UTF-8';
			$mail1->Body = $html;
			$mail1->send();
			echo $html;
		break;
		case 5:
			$result='';$curinsql='';
			if($_GET['term']!='') {$curin=$_GET['term'];$curinsql="where `name` like '%".$curin."%' ";}
			
			$selectimgs = CMS::$db->query("select * from `delivery` ".$curinsql." limit 10 ");
			if(!empty($selectimgs)) foreach ($selectimgs as $i => $dd)
			{
				if($result!='') $result.=',';
				$result.='{"label":"'.$dd['name'].'","value":"'.$dd['name'].'"}';
			}
			if($result!='') $result='['.$result.']';
			echo $result;
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