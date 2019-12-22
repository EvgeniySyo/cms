<?PHP
if(!isset($_GET['admin_page']))
{
	$admin_page = "start";
}
if($admin_page == "start")
{
?>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<!-- img !-->
  <tr>
    <td align="center" valign="top"><a href="main-blocks-setting.pl"><img src="../templates/admin/images/panel/block_set.png" alt="" border="0"></a></td>
    <td align="center" valign="top"><a href="main-blocks-acsess_level.pl"><img src="../templates/admin/images/panel/block_r.png" alt="" border="0"></a></td>
    <td align="center" valign="top"><a href="main-blocks-install.pl"><img src="../templates/admin/images/panel/block_install.png" alt="" border="0"></a></td>
  </tr>
  <!-- end !-->
  <tr>
    <td width="30%" align="center" valign="top"><a href="main-blocks-setting.pl"><?php echo _admin_blocks73_; ?></a></td>
    <td width="30%" align="center" valign="top"><a href="main-blocks-acsess_level.pl"><?php echo _admin_blocks74_; ?></a></td>
    <td width="30%" align="center" valign="top"><a href="main-blocks-install.pl"><?php echo _admin_blocks75_; ?></a></td>
  </tr> 
   <tr>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="16" valign="top"><img src="../templates/admin/images/panel/reload.png" alt="" border="0"></td>
    <td><? echo _admin_main1169_; ?></td>
  </tr>
</table>
    </td>
    <td align="left" valign="top">   
        <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="16" valign="top"><img src="../templates/admin/images/panel/reload.png" alt="" border="0"></td>
    <td><? echo _admin_main1170_; ?></td>
  </tr>
</table>
</td>
    <td align="left" valign="top">
         <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="16" valign="top"><img src="../templates/admin/images/panel/yes.png" alt="" border="0"></td>
    <td><? echo _admin_main1171_; ?></td>
  </tr>
</table>   
</td>
  </tr> 
</table>
<?PHP
}
else
{
	$admin_page = $_GET['admin_page'];
	if(!file_exists(DATA_PATH."/admin/main/blocks/".$admin_page.".php"))
	{
		$admin_page = "start";
		include(DATA_PATH."/admin/main/blocks.php");
	}
	else
	{
		include(DATA_PATH."/admin/main/blocks/".$admin_page.".php");
	}
}
?>