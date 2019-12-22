<?php

$TemplatesSection = CMS::SectionFile('installblock');

echo CMS::SectionAdmin($TemplatesSection,1,"","");
echo CMS::SectionAdmin($TemplatesSection,3,"","");

if(isset($_POST['add']))
{
	if(file_exists("block/".$_POST['mod']."/index.php"))
	{
		$nameBlock = $_POST['mod'];
		if(file_exists(DATA_PATH."/block/".$_POST['mod']."/mysql.php")) include(DATA_PATH."/block/".$_POST['mod']."/mysql.php");
		if(file_exists(DATA_PATH."/block/".$_POST['mod']."/system.php")) include(DATA_PATH."/block/".$_POST['mod']."/system.php");
		//if(file_exists(DATA_PATH."/block/".$_POST['mod']."/config.php")) chmod(DATA_PATH."/block/".$_POST['mod']."/config.php", 666);
		Simple_DbApi::insert_db("block","name_block,posision,ves,status,for_block,install,titul_on","".$_POST['mod']."<><>left<><>0<><>off<><>0<><>yes<><>on");

		if(file_exists("block/".$_POST['mod']."/title.php")) include("block/".$_POST['mod']."/title.php");
		if(empty($BlockName)) $BlockName = $_POST['mod'];

		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> установил блок <b>'.$BlockName.'</b>. Раздел <a target="_blank" href="main-blocks-install.pl"><b>Установка блоков</b></a>');
		
		echo CMS::AlertWindow('Успешно','Блок установлен',1,0);
	}
}

$DirList = scandir("block/");

$SelectListBlock = Simple_DbApi::select_db("block","*","install","yes","","","","");

$LiStInstallBlock = array();

if (!empty($SelectListBlock)) {
    foreach ($SelectListBlock as $n => $nm1)
    {
        $LiStInstallBlock[$n] = $nm1['name_block'];
    }
}


$ListFind = 0;

for ($i=2;$i<count($DirList);$i++)
{
	$FindBlock = false;
	for($j=0;$j<count($LiStInstallBlock);$j++) if(is_dir("block/".$DirList[$i]) && $LiStInstallBlock[$j] == $DirList[$i]) $FindBlock = true;
	if($FindBlock == false)
	{
		if(file_exists("block/".$DirList[$i]."/index.php"))
		{
			if(file_exists("block/".$DirList[$i]."/title.php")) include("block/".$DirList[$i]."/title.php");
			if(empty($BlockName)) $BlockName = "no name";

			if(file_exists('templates/admin/ico/block/'.$DirList[$i].'.png')) $image = 'templates/admin/ico/block/'.$DirList[$i].'.png';
			else $image = 'templates/admin/ico/admin/blockdevice.png';

			$ListBlock .= CMS::SectionAdmin($TemplatesSection,6,"{name},{id},{img}","".$BlockName."<><>".$DirList[$i]."<><>".$image);
			$ListFind =+ 1;
		}
	}

}

if($ListFind == 0) echo CMS::SectionAdmin($TemplatesSection,8,"","");
else
{
	echo CMS::SectionAdmin($TemplatesSection,4,"","");
	echo $ListBlock;
	echo CMS::SectionAdmin($TemplatesSection,5,"","");
}


echo CMS::SectionAdmin($TemplatesSection,2,"","");

?>