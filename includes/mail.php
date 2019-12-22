<?php
if (isset($_POST['mailUserTo'])) 
{
	$mailTo = $_POST['mailUserTo'];
	$phoneTo = strip_tags($_POST['phoneTo']);

	if (!empty($phoneTo) && $phoneTo != 'номер телефона') {

        if(ereg("^(([[:alnum:]]+(-|_)?[[:alnum:]]+)\.?)+@([[:alnum:]]+(-|_)?[[:alnum:]]+\.)+[[:alnum:]]+$", $mailTo)) $valid = 1;
		else $valid = 2;
		
		echo $valid;

		if ($valid == 1) {
			$countMailTo = $simple_db_work -> CountTable('mailing', 'e-mail', $mailTo);
			if ($countMailTo == 0) {
				$ipUser = $_SERVER['REMOTE_ADDR'];
				$dateUser = date("d.m.o H:m");
				$simple_db_work -> insert_db('mailing', "name,e-mail,ip,date", $phoneTo . "<><>" . $mailTo . "<><>" . $ip . "<><>" . $date);
				$ALertSend = '

	<div class="alertSend">
	<p>Поздравляем вы подписались на рассылку</p>
	<input type="button" class="button-ok" onclick="closedAlert();" value="ок" />
	</div>
	';
			} else {
				$ALertSend = '

	<div class="alertSend">
	<p>Данный e-mail уже подписана на расслыку</p>
	<input type="button" class="button-ok" onclick="closedAlert();" value="ок" />
	</div>
	';
			}
		} else {
			$ALertSend = '

	<div class="alertSend">
	<p>Неверный e-mail</p>
	<input type="button" class="button-ok" onclick="closedAlert();" value="ок" />
	</div>
	';
		}
	} else {
		$ALertSend = '

	<div class="alertSend">
	<p>Не указан номер телефона</p>
	<input type="button" class="button-ok" onclick="closedAlert();" value="ок" />
	</div>
	';
	}
}
?>