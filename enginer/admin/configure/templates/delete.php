<?PHP
if(!isset($_GET['name_tamlates']))
{
	$mysql_select["templates"] = CMS::$db->query("SELECT * FROM `".$db["prefix"]."templates`");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="200"><h5><? echo _admin_templates4_;?></h5></td>
    <td><h5><? echo _admin_templates5_;?></h5></td>
    <td><h5><? echo _admin_templates6_;?></h5></td>
    <td><h5><? echo _admin_templates7_;?></h5></td>
  </tr>
<?php
foreach ($mysql_select["templates"] as $i => $nt)
{
	if($nt['status'] != "no" && $nt['name'] != "admin")
	{
		if(file_exists("templates/".$nt["name"]."/".$nt["name"].".png"))
		{
			$image["templates"] = "<img src=\"../templates/".$nt["name"]."/".$nt["name"].".png\" alt=\"".$nt["name"]."\" border=\"0\" widht=\"150\" height=\"150\">";
		}
		else
		{
			$image["templates"] = "<img src=\"../images/notamplates.png\" alt=\"".$nt["name"]."\" border=\"0\">";
		}
		echo "<tr>
    <td>".$image["templates"]."</td>
    <td>".$nt["name"]."</td>";
		echo "
		<td>
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"26\"><img src=\"../templates/admin/images/panel/del.png\" alt=\""._admin_templates9_."\" border=\"0\"></td>
    <td><a href=\"configure-templates-delete-del-".$nt['name'].".pl\">"._admin_templates20_."</a></td>
  </tr>
</table>
</td>";
		if(!file_exists("templates/".$nt["name"]."/info"))
		{
			echo "<td>"._admin_templates10_."</td>";
		}
		else
		{
			include("templates/".$nt["name"]."/info");
			echo "<td><b>"._admin_templates11_."</b> ".htmlspecialchars($date_templates)."<br>
		<b>"._admin_templates12_."</b> ".htmlspecialchars($autor_templates)."<br>
		<b>"._admin_templates13_."</b> ".htmlspecialchars($validate_code_templates)."<br>
		<b>"._admin_templates14_."</b> <a href=\"".htmlspecialchars($url_autor)."\" targer=\"_blank\">".htmlspecialchars($url_autor)."</a><br>
		<b>"._admin_templates15_."</b> <a href=\"mailto:".htmlspecialchars($email_templates)."\">".htmlspecialchars($email_templates)."</a><br>
		<b>"._admin_templates16_."</b>".htmlspecialchars($icq_templates)."<br>
		<b>"._admin_templates17_."</b> <br>".htmlspecialchars($info_templates)."
		</td>";
		}
		echo "</tr>";
	}
}
?>
</table>
<?PHP

}
else
{
	$reload_templates = $_GET['name_tamlates'];
	if($reload_templates == $html["templates"])
	{
		echo "
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"128\"><img src=\"../images/stop.png\" alt=\"\" border=\"0\"></td>
    <td>"._admin_templates37_."<br>
		<a href=\"configure-templates-delete.pl\" >"._admin_templates31_."</a>
		</td>
  </tr>
</table>";
		echo "<hr>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
  <tr>
    <td width=\"24\"><img src=\"../templates/admin/images/panel/in.png\" alt=\"\" border=\"0\"></td>
    <td>"._admin_templates38_." <a href=\"configure-templates-reload.pl\">"._admin_templates39_."</a>.
		</td>
  </tr>
</table>";
	}
	else
	{
		$mysql_select["templates"] = CMS::$db->query("SELECT * FROM `".$db["prefix"]."templates`");
		foreach ($mysql_select["templates"] as $i => $nt)
		{
			if($reload_templates == $nt['name'])
			{
				$set_templates = true;
				break;
			}
			else
			{
				$set_templates = false;
			}
		}
		if($set_templates == true)
		{
			if(count($mysql_select["templates"]) > 1)
			{
				/*
				$dir_templates = "templates/".$reload_templates;
				function del_directory($dir_templates)
				{
				$dir_scan = opendir($dir_templates);
				while ($file_templates = readdir($dir_scan))
				{
				if(is_file($dir_templates."/".$file_templates)) unlink($dir_templates."/".$file_templates);
				else if(is_dir($dir_templates."/".$file_templates) && $file_templates != "." && $file_templates != "..")
				{
				del_directory($dir_templates);
				}
				}
				closedir($dir_scan);
				}
				del_directory($dir_templates);
				rmdir("templates/".$reload_templates);
				*/
				$mysql_select["del_templates"] = CMS::$db->query("DELETE FROM `".$db["prefix"]."templates` WHERE `name`='".$reload_templates."'");
				echo "
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"128\"><img src=\"../images/yes.png\" alt=\"\" border=\"0\"></td>
    <td>"._admin_templates32_."<br>
		<a href=\"configure-templates-delete.pl\" >"._admin_templates31_."</a>
		</td>
  </tr>
</table>";
				echo "<hr>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
  <tr>
    <td width=\"24\"><img src=\"../templates/admin/images/panel/in.png\" alt=\"\" border=\"0\"></td>
    <td>"._admin_templates33_." <a href=\"configure-templates-install.pl\">"._admin_templates34_."</a>. "._admin_templates35_." <b>$reload_templates</b>  "._admin_templates36_."<br>
		</td>
  </tr>
</table>";
			}
			else
			{
				//echo "� ��� ���������� ������������ ������";
			}
			
		}
		else
		{

		}
	}
}
include(DATA_PATH."/admin/configure/templates/down.php");
?>