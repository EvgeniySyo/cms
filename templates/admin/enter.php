<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Панель администратора</title>
<link href="../<?=TEMPLATES?>/admin/css/style1.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
<div class="all">
<div class="pro100">Sim<span class="RC">ple</span>cms</div>
<div class="line"></div>
<p class="text">Вход в административный раздел</p>
<div class="img"><img src="../<?=TEMPLATES?>/admin/images1/i.png" alt="" /></div>
<form action="admin.pl" method="post">
<table class="table">
<tr>
<td class="t">Логин:</td><td><input type="text" name="login" /></td>
</tr>
<tr>
<td class="t">Пароль:</td><td><input type="password" name="password" /></td>
</tr>
<tr><td></td><td class="t"></td></tr>
<tr>
<td></td><td class="t"><input type="submit" style="cursor:pointer" name="go" value="" class="submit" /></td>
</tr>
</table>
</form>
<div class="clear"></div>
<div class="line"></div>
</div>
</body>
</html>   