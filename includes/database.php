<?php

// Include all credentials, make sure this is pointing at the right place
@include_once '../../../credentials.php';

// Connect to mysql via pdo and leave the $pdo for further use
try {
	$pdo = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';', $db_user, $db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
} catch(Exception $e) {
	throw new PDOException('Could not connect to database.');
}