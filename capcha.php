<?php
session_start();
$text_capcha = empty($_SESSION['captcha']) ? 'error' :  $_SESSION['captcha'];
$rand["x"] = rand(5,26);
$rand["y"] = rand(25,40);
$rand["angle"] = rand(-8,8);
$rand["height"] = rand(14,16);
$rand["col_1"] = 111;
$rand["col_2"] = 111;
$rand["col_3"] = 111;
$rand["col_4"] = 0;
$cap["file"] = "images/capcha/2.jpg";
$cap["size"] = getimagesize($cap["file"]);
$cap["color"] = imagecreatetruecolor($cap["size"][0],$cap["size"][1]);
$cap["create"] = imagecreatefromjpeg($cap["file"]);
imagecopyresampled($cap["color"],$cap["create"],0,0,0,0,$cap["size"][0],$cap["size"][1],$cap["size"][0],$cap["size"][1]);
$cap["font"] = "font/5.ttf";
$cap["img_color"] = imagecolorallocatealpha($cap["create"],$rand["col_1"],$rand["col_2"],$rand["col_3"],$rand["col_4"]);
$cap["text"] = $text_capcha;
$cap["height"] = $rand["height"];
$cap["angle"] = $rand["angle"];
$cap["box"] = imagettftext($cap["create"],$cap["height"],$cap["angle"],$rand["x"],$rand["y"],$cap["img_color"],$cap["font"],$cap["text"]);
header ("Content-type: image/jpeg");
imagejpeg($cap["create"]);
imagedestroy($cap["color"]);
imagedestroy($cap["create"]);
?>