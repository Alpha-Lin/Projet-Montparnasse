<?php
$options =
[
	PDO::ATTR_EMULATE_PREPARES => false
];

try{
	$bdd = new PDO('mysql:host=localhost;dbname=stonksbay', 'stonksbay', '1234'); // user et mdp à changer par la suite
}catch(PDOException $pe){
	die('<p>Erreur lors de l\'accès à la base de donnée : </p>');
}

session_start();
