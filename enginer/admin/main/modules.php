<?PHP
if(!isset($_GET['admin_page'])) $admin_page = "start";
else 
{
	$admin_page = $_GET['admin_page'];
	if(!file_exists(DATA_PATH."/admin/main/modules/".$admin_page.".php"))
	{
		$admin_page = "start";
		include(DATA_PATH."/admin/main/modules.php");
	}
	else include(DATA_PATH."/admin/main/modules/".$admin_page.".php");
}
?>