<?php

if(isset($_POST['capcha']))
{
	if($_SESSION['captcha']==$_POST['capcha']) echo 1;
	else echo 0;
}
?>