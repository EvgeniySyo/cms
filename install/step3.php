<?php if(INSTALL_PRO100 != "yes") exit; ?>
<div class="all">
<div class="pro100"><span class="setL">УСТАНОВКА</span><br /><span class="setB">системы управления сайтом</span><br />Sim<span class="RC">ple</span>cms</div>
<div class="line"></div>
<p class="step"><span class="num">3.</span>Настройка базы данных</p>
<form action="?step=3" method="post">
<table class="table2">

<tr>
<td class="St1">
<!-- window -->
<div id="1" class="block" onmouseout="Invisble(1)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">адрес сервера mysql</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Сервер: <img src="images/q.png" onmouseover="Visible(1)" alt="" /></td><td><input type="text" class="stepInput" name="serverDB" value="<?=!empty($_SESSION['serverDB']) ? $_SESSION['serverDB'] : '';?>" /></div></td>
</tr>
<tr>
<td class="St1">
<!-- window -->
<div id="2" class="block" onmouseout="Invisble(2)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">по какому порту подрубаться к серверу</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Порт: <img src="images/q.png" onmouseover="Visible(2)" alt="" /></td><td><input type="text" class="stepInput" name="portDB" value="<?=!empty($_SESSION['port']) ? $_SESSION['port'] : '';?>" />
</div>
</td>
</tr>
<tr>
<td class="St1">
<!-- window -->
<div id="3" class="block" onmouseout="Invisble(3)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">логин к базе данных</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Логин: <img src="images/q.png" onmouseover="Visible(3)" alt="" /></td><td><input type="text" class="stepInput" name="loginDB" value="<?=!empty($_SESSION['Login']) ? $_SESSION['Login'] : '';?>" />
</div>
</td>
</tr>
<tr>
<td class="St1">
<!-- window -->
<div id="4" class="block" onmouseout="Invisble(4)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">пароль к базе данных</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Пароль: 

<img style="cursor:pointer"  onmouseover="Visible(4)" src="images/q.png" alt="" />
</div>
</td><td><input type="text" class="stepInput" name="passDB" value="<?=!empty($_SESSION['passDB']) ? $_SESSION['passDB'] : '';?>" /></td>
</tr>
<tr>
<td class="St1">
<!-- window -->
<div id="5" class="block" onmouseout="Invisble(5)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">название вашей БД</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
БД: 

<img style="cursor:pointer"  onmouseover="Visible(5)" src="images/q.png" alt="" />
</div>
</td><td><input type="text" class="stepInput" name="db" value="<?=!empty($_SESSION['DB']) ? $_SESSION['DB'] : '';?>" /></td>
</tr>

<tr>
<td class="St1">
<!-- window -->
<div id="7" class="block" onmouseout="Invisble(7)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">префикс БД</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
префикс: 

<img style="cursor:pointer"  onmouseover="Visible(7)" src="images/q.png" alt="" />
</div>
</td><td><input type="text" class="stepInput" name="prefix" value="<?=!empty($_SESSION['prefix']) ? $_SESSION['prefix'] : '';?>" /></td>
</tr>
<tr>
</table>

<?=$warning;?>

<div class="nextGo" style="margin-top:80px;">
<input type="submit" class="textBack" name="back" value="назад" />
<input type="submit" name="step" class="textNext" value="вперед" />
</form>