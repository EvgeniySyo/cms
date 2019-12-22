<?php if(INSTALL_PRO100 != "yes") exit; ?>
<div class="all">
<div class="pro100"><span class="setL">УСТАНОВКА</span><br /><span class="setB">системы управления сайтом</span><br />Sim<span class="RC">ple</span>cms</div>
<div class="line"></div>
<p class="step"><span class="num">2.</span>Настройки сайта</p>
<form action="?step=2" method="post">
<table class="table2">

<tr>
<td class="St1">
<!-- window -->
<div id="1" class="block" onmouseout="Invisble(1)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">В данном поле указывает название сайта</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Название сайта: <img src="images/q.png" onmouseover="Visible(1)" alt="" /></td><td><input type="text" class="stepInput" name="nameSite" value="<?=$_SESSION['NameSite']?>" /></div></td>
</tr>
<tr>
<td class="St1">
<!-- window -->
<div id="2" class="block" onmouseout="Invisble(2)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">Ключевые слова для сайта</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Ключевые слова: <img src="images/q.png" onmouseover="Visible(2)" alt="" /></td><td><input type="text" class="stepInput" name="keySite" value="<?=$_SESSION['keywords']?>" />
</div>
</td>
</tr>

<tr>
<td class="St1">
<!-- window -->
<div id="4" class="block" onmouseout="Invisble(4)">
<div class="imgWin"><img src="images/qh .png" alt="" /></div>
<div class="TopWin"></div>
<div class="centerWin">Абсолютный путь до папки enginer</div>
<div class="BottomWin"></div>
</div>
<!-- emd -->
<div style="text-align:right">
Путь до папки engine: 

<img style="cursor:pointer"  onmouseover="Visible(4)" src="images/q.png" alt="" />
</div>
</td><td><input type="text" class="stepInput" name="path" value="<?=$PathTo?>" /></td>
</tr>
</table>
<?=$warning?>


<div class="nextGo" style="margin-top:80px;position:absolute;padding-left:140px;">
<input type="submit" class="textBack" name="back" value="назад" />
<input type="submit" name="step" class="textNext" value="вперед" />
</form>