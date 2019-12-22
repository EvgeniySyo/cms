<?PHP
if(!isset($_POST["edit_go"]))
{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50"><IMG src="../templates/admin/images/panel/asess_module.png" alt="" border="0"></td>
    <td><STRONG><?php echo _admin_modules1_;?></STRONG></td>
  </tr>
</table>
<?PHP
$mysql["select_modules"] = CMS::$db->query("SELECT * FROM `".$db["prefix"]."modules`");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30"><STRONG><?php echo _admin_modules2_;?></STRONG></td>
    <td><STRONG><?php echo _admin_modules3_;?></STRONG></td>
    <td><STRONG><?php echo _admin_modules4_;?></STRONG></td>
    <td><STRONG><?php echo _admin_modules5_;?></STRONG></td>
  </tr>
<?PHP
foreach ($mysql["select_modules"] as $i => $nm)
{
	if($nm['install'] == "yes")
	{
		if(!isset($color))
		{
			$color = "style=\"background-color:#D8E6FA;\"";
		}
		else
		{
			unset($color);
		}
?>
<FORM action="main-modules-acess.pl" method="POST">
  <tr>
    <td height="40" align="center" <?php echo $color;?>><b><? echo $nm['name'] ?></b></td>
    <?PHP
    if($nm['acsess'] == 1)
    {
    	$user = "selected";
    }
    if($nm['acsess'] > 0 && $nm['acsess'] < 200)
    {
    	$moder = "selected";
    }
    if($nm['acsess'] > 199 )
    {
    	$admin = "selected";
    }
    if($nm['acsess'] == 0 )
    {
    	$guest = "selected";
    }
    if($nm['acsess'] < 0 )
    {
    	$all = "selected";
    }
    if($nm['status'] == "on")
    {
    	$status_mod = "<font color=\"green\">"._admin_modules6_."</font>";
    }
    else
    {
    	$status_mod = "<font color=\"red\">"._admin_modules7_."</font>";
    }
    ?>
    <td height="40" <?php echo $color;?>>
    <SELECT class="button" name="acess">
    <OPTION <? echo $admin; ?>><?php echo _admin_modules8_;?></OPTION>
    <OPTION <? echo $moder; ?>><?php echo _admin_modules9_;?></OPTION>
    <OPTION <? echo $user; ?>><?php echo _admin_modules10_;?></OPTION>
    <OPTION <? echo $guest; ?>><?php echo _admin_modules11_;?></OPTION>
    <OPTION <? echo $all; ?>><?php echo _admin_modules12_;?></OPTION>
    </SELECT>
    </td>
    <td height="40" <?php echo $color;?>><? echo $status_mod; ?></td>
     <INPUT type="hidden" name="modules" value="<? echo $nm['name']; ?>"> 
    <td height="40" <?php echo $color;?>><INPUT type="submit" name="edit_go" class="button" value="<?php echo _admin_modules13_;?>"></td>
  </tr>
  </form>
<?PHP
unset($admin);
unset($moder);
unset($user);
unset($guest);
unset($all);
	}
}
echo "</table>";

}
else
{
	if($_POST['acess'] == _admin_modules8_)
	{
		$acsess = 200;
	}
	if($_POST['acess'] == _admin_modules9_)
	{
		$acsess = 100;
	}
	if($_POST['acess'] == _admin_modules10_)
	{
		$acsess = 1;
	}
	if($_POST['acess'] == _admin_modules11_)
	{
		$acsess = 0;
	}
	if($_POST['acess'] == _admin_modules12_)
	{
		$acsess = -10;
	}
	$mysql["update_templates"] = CMS::$db->query("UPDATE `".$db["prefix"]."modules` SET `acsess` = '$acsess' WHERE `name` = '".$_POST["modules"]."'");
	
	echo "
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"128\"><img src=\"../images/yes.png\" alt=\"\" border=\"0\"></td>
    <td>"._admin_modules14_."<br>
		<a href=\"main-modules-acess.pl\" >"._admin_modules15_."</a>
		</td>
  </tr>
</table>";
	echo "<hr>
		<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
  <tr>
    <td width=\"24\"><img src=\"../templates/admin/images/panel/in.png\" alt=\"\" border=\"0\"></td>
    <td>"._admin_modules16_."
		</td>
  </tr>
</table><hr>";
}
?>