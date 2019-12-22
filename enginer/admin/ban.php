<?php

if(isset($_POST['add']))
{
	$ip = $_POST['ip'];
	$alert = strip_tags($_POST['alert']);

	### TEST IP ###

	list($ip1,$ip2,$ip3,$ip4) = explode('.',$ip,4);
	if(is_numeric($ip1) && is_numeric($ip2) && is_numeric($ip3) && is_numeric($ip4))
	{
		Simple_DbApi::insert_db('ban_ip','id,ip,alert,date','<><>'.$ip.'<><>'.$alert.'<><>'.date('Y-n-j'));
		echo CMS::AlertWindow('Успешно','ip адрес добавлен',1,0);
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> заблокировал доступ к сайту ip адрес: <b>'.$ip.'</b>. Раздел <a target="_blank" href="ban.pl"><b>бан ip</b></a>');
	}
	else echo CMS::AlertWindow('Ошибка','Не вереный ip адрес',3,0);
}

if(isset($_POST['ban']) && is_numeric($_POST['id']))
{
	$SelectIp = Simple_DbApi::select_db('ban_ip','ip','id',$_POST['id'],'','','','');
	if (!empty($SelectIp))
	{
		$nd = current($SelectIp);
		if(file_exists(DATA_PATH.'ipban/'.$nd['ip'])) unlink(DATA_PATH.'ipban/'.$nd['ip']);
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> разблокировал доступ к сайту ip адрес: <b>'.$nd['ip'].'</b>. Раздел <a target="_blank" href="ban.pl"><b>бан ip</b></a>');
		Simple_DbApi::delete_db('ban_ip','id',$_POST['id']);
		echo CMS::AlertWindow('Успешно','ip адрес удален',1,0);
	}
}

if(isset($_POST['edit']) && is_numeric($_POST['id']))
{
	$SelectIp = Simple_DbApi::select_db('ban_ip','ip','id',$_POST['id'],'','','','');
	if(!empty($SelectIp))
	{
		$nd = current($SelectIp);
		$alert = strip_tags($_POST['alert']);
		$fp = fopen(DATA_PATH.'ipban/'.$nd['ip'],'w');
		flock($fp,LOCK_EX);
		fwrite($fp,$alert);
		flock($fp,LOCK_UN);
		fclose($fp);
		Simple_DbApi::update_db('ban_ip','alert',$alert,'id',$_POST['id']);
		echo CMS::AlertWindow('Успешно','данные обновлены',1,0);
		
	}
}

$TemplatesSection = CMS::SectionFile('ban');

echo CMS::SectionAdmin($TemplatesSection,1,'','');

$SelectBanUser = Simple_DbApi::select_db('ban_ip','*','','','ip',1,'','');
if(!empty($SelectBanUser))
{
	foreach ($SelectBanUser as $i => $ni)
	{
		if(!file_exists(DATA_PATH.'ipban/'.$ni['ip']))
		{
			$fp = fopen(DATA_PATH.'ipban/'.$ni['ip'],'w');
			flock($fp,LOCK_EX);
			fwrite($fp,$ni['alert']);
			flock($fp,LOCK_UN);
			fclose($fp);
		}
		$list .= CMS::SectionAdmin($TemplatesSection,4,'{ip},{window1},{id},{window}',$ni['ip'].'<><>'.CMS::AlertWindow('Внимание',CMS::SectionAdmin($TemplatesSection,5,'{ip},{id}',$ni['ip'].'<><>'.$ni['id']),2,'a'.$ni['id']).'<><>'.$ni['id'].'<><>'.CMS::AlertWindow('ip '.$ni['ip'],CMS::SectionAdmin($TemplatesSection,6,'{ip},{id},{alert}',$ni['ip'].'<><>'.$ni['id'].'<><>'.$ni['alert']),4,$ni['id']));
	}

	echo CMS::SectionAdmin($TemplatesSection,3,'{list}',$list);

}

echo CMS::SectionAdmin($TemplatesSection,2,'','');

?>