<?php if(INSTALL_PRO100 != "yes") exit; ?>
<div class="all">
<div class="pro100"><span class="setL">УСТАНОВКА</span><br /><span class="setB">системы управления сайтом</span><br />Sim<span class="RC">ple</span>cms</div>
<div class="line"></div>
<p class="step"><span class="num">4.</span>Создание учётной записи администратора</p>
<form action="?step=4" method="post">
<table class="table2">

<tr>
<td class="St1">
<!-- window -->
<div id="1" class="block" onmouseout="Invisble(1)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">логин администратора</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Логин: <img src="images/q.png" onmouseover="Visible(1)" alt="" /></td><td><input type="text" class="stepInput" name="login" value="" /></div></td>
</tr>
<tr>
<td class="St1">
<!-- window -->
<div id="2" class="block" onmouseout="Invisble(2)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">ппароль администратора</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Пароль: <img src="images/q.png" onmouseover="Visible(2)" alt="" /></td><td><input type="text" class="stepInput" name="pass" value="" />
</div>
</td>
</tr>
<tr>
<td class="St1">
<!-- window -->
<div id="3" class="block" onmouseout="Invisble(3)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">потоврить пароль</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Повторите пароль: <img src="images/q.png" onmouseover="Visible(3)" alt="" /></td><td><input type="text" class="stepInput" name="replaypass" value="" />
</div>
</td>
</tr>
<tr>
<td class="St1">
<!-- window -->
<div id="4" class="block" onmouseout="Invisble(4)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">ваш почтовый адрес</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
e-mail: 

<img style="cursor:pointer"  onmouseover="Visible(4)" src="images/q.png" alt="" />
</div>
</td><td><input type="text" class="stepInput" name="mail" value="" /></td>
</tr>


</table>

<?=$warning?>

<div class="nextGo" style="margin-top:80px;">
<input type="submit" class="textBack" name="back" value="назад" />
<input type="submit" name="step" class="textNext" value="вперед" />
</form>