<?php
$host = 'mysql5-15.perso';
$database = 'bastiengiago';
$user = 'bastiengiago';
$password = 'sh7n9wn7';

try{
	$IagoDB = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}
catch(Exception $e){
	echo 'Erreur : '.$e->getMessage().'<br />';
	echo 'N° : '.$e->getCode();
}
?>
