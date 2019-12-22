<?php
if(!isset($_GET['ajax']) && !isset($_GET['ajax1']))
{
	$fasle_stat = false;
	$TimeGen = CMS::timing_site_start();
	if(isset($_SESSION["login_admin"]))
	{
		$sels = Simple_DbApi::select_db("accounts","*","login",htmlspecialchars($_SESSION["login_admin"]),"","",0,1);
		if(!empty($sels))
		{
			$ng = current($sels);
			if($_SESSION["pass_admin"] == $ng['password'] && $ng['acsess_level'] > 100)
			{
				Simple_Error::Test_File_Insite(TEMPLATES."/admin/index.php",SIMPLE_NOT_FILE." ".TEMPLATES."/admin/index.php");
				include(TEMPLATES."/admin/index.php");
			}
			else
			{
				unset($_SESSION["login_admin"]);
				include(TEMPLATES."/admin/enter.php");
			}
		}
	}
	else
	{
		$mysql["admin"] = Simple_DbApi::select_db("accounts","*","login",htmlspecialchars($_POST['login']),"","",0,1);
		$na = current($mysql["admin"]);
		$go = true;
		if($_POST['login'] == $na['login'])
		{
            if(md5(md5($_POST['password'])) == $na['password'])
            {
                if($na['acsess_level'] > 100)
                {
                    $_SESSION["acsses_admin"] = $na['acsess_level'];
                    $_SESSION["login_admin"] = $na['login'];
                    $_SESSION["pass_admin"] = $na['password'];
                    include(TEMPLATES."/admin/index.php");
                    CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> вошел в систему управления');
                    Simple_DbApi::insert_db('log','id,ip,date,time','<><>'.$_SERVER['REMOTE_ADDR'].'<><>'.date('Y-m-d').'<><>'.date('H:i:s'));

                }
                else include(TEMPLATES."/admin/enter.php");
            }
            else
            {
                CMS::UserEvents('Указан неверный пароль для пользователя <b>'.htmlspecialchars($_POST['login']).'</b>. Данные ввода пароля: '.htmlspecialchars($_POST['password']));
                include(TEMPLATES."/admin/enter.php");
            }

			$empety_login = false;
			$go = false;
		}
		else 
		{
			CMS::UserEvents('Указан неверный пользователь <b>'.htmlspecialchars($_POST['login']).'</b>');
		}
		$empety_login = true;
		if($empety_login == true && $go == true) include(TEMPLATES."/admin/enter.php");
	}

	$all_time = CMS::timing_end_site($TimeGen);
}
if(isset($_GET['ajax']))
{
	if(CMS::TestAdminAcount() == 2)
	{
		$_GET['config'] = 'modules';
		$_GET['admin_page'] = 'setting';
		$_GET['name_tamlates'] = $_SESSION['SIMPLE_MODULE'];

		include(DATA_PATH."/admin/content.php");
	}
}
if(isset($_GET['ajax1']))
{
	if(CMS::TestAdminAcount() == 2)
	{
		$_GET['config'] = $_SESSION['SIMPLE_BLOCK'];
		$_GET['admin'] = $_SESSION['SIMPLE_MAIN'];
		include(DATA_PATH."/admin/content.php");
	}
}
?>