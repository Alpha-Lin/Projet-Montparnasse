<?php
require "img-upload.php";

$name = "elsassfrei.png"; //change of file name
$stmt =$pdo->prepare("SELECT * FROM `imz` WHERE `img_name`=?");
$stmt->execute([$name]);
$img =$stmt->fetch();
$img =$img['img_data'];

header("Content-type: image/jpeg");
echo $img;
>
