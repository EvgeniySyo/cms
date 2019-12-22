<?php

$TemplatesSection = CMS::SectionFile('user');

# ADD NEW USER ###
if(isset($_POST['add']))
{
	if(!empty($_POST['login']) && !empty($_POST['pass']) && !empty($_POST['mail']))
	{
		$ac = 200;
		$date = date("j.m.Y-G:i:s");
		$test = Simple_DbApi::select_db("accounts","*","login",strip_tags($_POST['login']),"","","","");
		if(count($test) == 0)
		{
			Simple_DbApi::insert_db("accounts","user_id,login,password,mail,acsess_level,ip_user,date","<><>".strip_tags($_POST['login'])."<><>".md5(md5($_POST['pass']))."<><>".strip_tags($_POST['mail'])."<><>".$ac."<><><><>".$date."");
			echo CMS::AlertWindow('Успешно','Пользователь создан',1,0);
			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> добавил пользователя <b>'.strip_tags($_POST['login']).'</b>. Раздел <a target="_blank" href="user.pl"><b>Учетные записи</b></a>');
		}
		else echo CMS::AlertWindow('Ошибка','Данный пользователь существует',3,0);
	}
}

# EDIT USER #


if(isset($_POST['edit']) && is_numeric($_POST['id']))
{

	$SelectUserUpdate = Simple_DbApi::select_db("accounts","*","user_id",$_POST['id'],"","",0,1);
	if(!empty($SelectUserUpdate))
	{
		$nj = current($SelectUserUpdate);
		if(empty($_POST['NewPass'])) Simple_DbApi::update_db("accounts","mail",strip_tags($_POST['NewMail']),"user_id",$_POST['id']);
		else Simple_DbApi::update_db("accounts","mail,password",strip_tags($_POST['NewMail'])."<><>".md5(md5($_POST['NewPass'])),"user_id",$_POST['id']);
		echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
		if(!empty($_POST['NewPass'])) $_SESSION["pass_admin"] = md5(md5($_POST['NewPass']));
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил данные для пользователя <b>'.$nj['login'].'</b>. Раздел <a target="_blank" href="user.pl"><b>Учетные записи</b></a>');
	}
}

# DELETE USER
if(isset($_POST['delete']) && is_numeric($_POST['id']))
{
	if(Simple_DbApi::CountTable('accounts','','') > 1) Simple_DbApi::delete_db("accounts","user_id",$_POST['id']);
	else echo CMS::AlertWindow('Ошибка','Удаление не возможно',3,0);

}


# END #

echo CMS::SectionAdmin($TemplatesSection,1,"","");

$SelectUser = Simple_DbApi::select_db("accounts","*","","","login",1,"","");
if (!empty($SelectUser)) {
    foreach ($SelectUser as $n => $nu)
    {
        $FUser .= CMS::SectionAdmin($TemplatesSection,3,"%USER%,%MAIL%,%LEVEL%,%IP%,%DATA%,%ID%,{window},{window1}","".$nu['login']."<><>".$nu['mail']."<><>".$nu['acsess_level']."<><>".$nu['ip_user']."<><>".$nu['date']."<><>".$nu['user_id']."<><>".CMS::AlertWindow($nu['login'],CMS::SectionAdmin($TemplatesSection,'3-1','%ID%,%MAIL%,%USER%',$nu['user_id'].'<><>'.$nu['mail'].'<><>'.$nu['login']),4,$nu['user_id'])."<><>".CMS::AlertWindow('Внимание!',CMS::SectionAdmin($TemplatesSection,'3-2','%ID%',$nu['user_id']),2,'-'.$nu['user_id']));
    }
}
echo CMS::SectionAdmin($TemplatesSection,2,"%INFO%",$FUser);

?>