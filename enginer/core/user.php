<?php
class Simple_User
{

	private function UnsetUser()
	{
		unset($_SESSION['SIMPLE_ID_USER']);
		unset($_SESSION['SIMPLE_LOGIN']);
		unset($_SESSION['SIMPLE_PASSWORD']);
	}

	public function TestUser()
	{
		if(is_numeric($_SESSION['SIMPLE_ID_USER']))
		{
			$SelectUser = Simple_DbApi::select_db('registration','login,password','id',$_SESSION['SIMPLE_ID_USER'],'','',0,1);
			if(!empty($SelectUser))
			{
				$nt = current($SelectUser);
				if($nt['login'] == $_SESSION['SIMPLE_LOGIN'] && $nt['password'] == $_SESSION['SIMPLE_PASSWORD']) return true;
				else
				{
					Simple_User::UnsetUser();
					return false;
				}
			}
			else
			{
				Simple_User::UnsetUser();
				return false;
			}
		}
		else
		{
			Simple_User::UnsetUser();
			return false;
		}
	}

	public function AuthUser()
	{
		if(isset($_POST['login']) && isset($_POST['password']))
		{
			require(DATA_PATH.'modules/registration/config.php');
			if(CMS::TestData($LoginUser,$_POST['login']) == true)
			{
				$SelectUser = Simple_DbApi::select_db('registration','id,login,password','login',$_POST['login'],'','',0,1);
				if(!empty($SelectUser))
				{
					$nt = current($SelectUser);
					if(md5(md5($_POST['password'])) == $nt['password'])
					{
						$_SESSION['SIMPLE_ID_USER'] = $nt['id'];
						$_SESSION['SIMPLE_LOGIN'] = $nt['login'];
						$_SESSION['SIMPLE_PASSWORD'] = $nt['password'];

						if($_POST['SaveUser'] == 'on')
						{
							$timeCookie = 17280000;
							setcookie("SIMPLE_ID_USER",$nt['id'],time()+$timeCookie);
							setcookie("SIMPLE_LOGIN",$nt['login'],time()+$timeCookie);
							setcookie("SIMPLE_PASSWORD",$nt['password'],time()+$timeCookie);
						}
					}
				}
			}
		}
		else
		{
			if(isset($_COOKIE['SIMPLE_ID_USER']) && $_COOKIE['SIMPLE_LOGIN'] && $_COOKIE['SIMPLE_PASSWORD'])
			{
				if(is_numeric($_COOKIE['SIMPLE_ID_USER']))
				{
					$SelectUser = Simple_DbApi::select_db('registration','id,login,password','id',$_COOKIE['SIMPLE_ID_USER'],'','',0,1);
					if(!empty($SelectUser))
					{
						$nt = current($SelectUser);
						if($nt['login'] == $_COOKIE['SIMPLE_LOGIN'] && $nt['password'] == $_COOKIE['SIMPLE_PASSWORD'])
						{
							$_SESSION['SIMPLE_ID_USER'] = $nt['id'];
							$_SESSION['SIMPLE_LOGIN'] = $nt['login'];
							$_SESSION['SIMPLE_PASSWORD'] = $nt['password'];
						}
						else
						{
							setcookie("SIMPLE_ID_USER",$nt['id'],time()-3600);
							setcookie("SIMPLE_LOGIN",$nt['login'],time()-3600);
							setcookie("SIMPLE_PASSWORD",$nt['password'],time()-3600);
						}
					}
				}
			}
		}
	}

	public function ExitUser()
	{
		Simple_User::UnsetUser();
		if(isset($_COOKIE['SIMPLE_LOGIN']))
		{
			setcookie("SIMPLE_ID_USER",$nt['id'],time()-3600);
			setcookie("SIMPLE_LOGIN",$nt['login'],time()-3600);
			setcookie("SIMPLE_PASSWORD",$nt['password'],time()-3600);
		}
	}
}
?>