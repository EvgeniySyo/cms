<?php
include("includes/js.php");

$TemplatesSection = CMS::SectionFile('SectorTheme');

if(!isset($_GET['config']))
{
	echo CMS::SectionAdmin($TemplatesSection,1,"","");
	echo CMS::SectionAdmin($TemplatesSection,3,"","");

	$SelectListTheme = Simple_DbApi::select_db("templates","*","","","name",1,"","");
    if (!empty($SelectListTheme)) {
        foreach ($SelectListTheme as $i => $nt)
        {
            if($nt['name'] != "admin")
            {
                if(file_exists("templates/".$nt['name']."/img.png")) $Img = "../templates/".$nt['name']."/img.png";
                else $Img = "../images/notamplates.png";

                $StatusTheme = CMS::SectionAdmin($TemplatesSection,6,"%THEME%",$nt['name']);

                if(file_exists("templates/".$nt["name"]."/info")) include("templates/".$nt["name"]."/info");
                if(empty($date_templates)) $date_templates = CMS::SectionAdmin($TemplatesSection,9,"","");
                if(empty($autor_templates)) $autor_templates = CMS::SectionAdmin($TemplatesSection,9,"","");
                if(empty($info_templates)) $info_templates = CMS::SectionAdmin($TemplatesSection,9,"","");
                if(empty($email_templates)) $email_templates = CMS::SectionAdmin($TemplatesSection,9,"","");

                $InfoTheme = CMS::SectionAdmin($TemplatesSection,8,"%DATA%,%AUTOR%,%MAIL%,%INFO%","".$date_templates."<><>".$autor_templates."<><>".$email_templates."<><>".$info_templates."");

                echo CMS::SectionAdmin($TemplatesSection,5,"%IMG%,%NAME%,%STATUS%,%INFO%","".$Img."<><>".$nt['name']."<><>".$StatusTheme."<><>".$InfoTheme."");
            }
        }
    }

	echo CMS::SectionAdmin($TemplatesSection,4,"","");
	echo CMS::SectionAdmin($TemplatesSection,2,"","");
}
else
{

	if(isset($_POST['save']))
	{
		for ($m=0;$m<count($_POST['name']);$m++) 
		{
			$NameSave = htmlspecialchars(trim($_POST['name'][$m]));
			$NameSave = str_replace("\\","",$NameSave);
			$SectorFormat .= "[section::".$_POST['IdSector'][$m]."]".trim($_POST['value'][$m])."::".$NameSave."[/section::".$_POST['IdSector'][$m]."]\n";
		}
		$fp = fopen(PATH_TO_THEME."sector","w");
		flock($fp,LOCK_EX);
		fwrite($fp,$SectorFormat);
		flock($fp,LOCK_UN);
		fclose($fp);
		echo CMS::SectionAdmin($TemplatesSection,17,"","");
		unset($SectorFormat);
	}

	echo CMS::SectionAdmin($TemplatesSection,1,"","");

	$SelectListTheme = Simple_DbApi::select_db("templates","*","name",$_GET['config'],"name",1,"","");
	if(!empty($SelectListTheme))
	{
		echo CMS::SectionAdmin($TemplatesSection,11,"","");

		$nt = current($SelectListTheme);

		if(file_exists("templates/".$nt['name']."/img.png")) $Img = "../templates/".$nt['name']."/img.png";
		else $Img = "../images/notamplates.png";

		if(file_exists("templates/".$nt["name"]."/info")) include("templates/".$nt["name"]."/info");
		if(empty($date_templates)) $date_templates = CMS::SectionAdmin($TemplatesSection,9,"","");
		if(empty($autor_templates)) $autor_templates = CMS::SectionAdmin($TemplatesSection,9,"","");
		if(empty($info_templates)) $info_templates = CMS::SectionAdmin($TemplatesSection,9,"","");
		if(empty($email_templates)) $email_templates = CMS::SectionAdmin($TemplatesSection,9,"","");

		$InfoTheme = CMS::SectionAdmin($TemplatesSection,8,"%DATA%,%AUTOR%,%MAIL%,%INFO%","".$date_templates."<><>".$autor_templates."<><>".$email_templates."<><>".$info_templates."");

		echo CMS::SectionAdmin($TemplatesSection,10,"%IMG%,%NAME%,%INFO%","".$Img."<><>".$nt['name']."<><>".$InfoTheme."");

		echo CMS::SectionAdmin($TemplatesSection,4,"","");



		if(file_exists("templates/".$nt['name']."/sector"))
		{
			echo CMS::SectionAdmin($TemplatesSection,12,"","");
			echo CMS::SectionAdmin($TemplatesSection,14,"","");
			$ReadSectorFile = file("templates/".$nt['name']."/sector");

			for ($s=0;$s<count($ReadSectorFile);$s++) $DataSector .= $ReadSectorFile[$s];

			preg_match_all("!\[section::(.*?)\](.*?)\[/section::(.*?)\]!si",$DataSector,$match);
							

			for ($g=0;$g<count($match[0]);$g++)
			{
				preg_match("!\[section::(.*?)\]!si",$match[0][$g],$SectionId);
				preg_match("!\[section::(.*?)\](.*?)\[/section::(.*?)\]!si",$match[0][$g],$SectorData);
				$SectionIdThis = $SectionId[1];
				$SectorDataThis = $SectorData[2];
				list($NameSector,$Data) = explode("::",$SectorDataThis,2);
				echo CMS::SectionAdmin($TemplatesSection,16,"%ID%,%NAMES%,%NAME%,%T%","".$SectionIdThis."<><>".$NameSector."<><>".$Data."<><>".$g."");
			}
			echo CMS::SectionAdmin($TemplatesSection,15,"","");
		}


		echo CMS::SectionAdmin($TemplatesSection,13,"","");
	}

	echo CMS::SectionAdmin($TemplatesSection,2,"","");
}
?>