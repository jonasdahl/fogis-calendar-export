<?php

require_once 'database.php';

// If there is no token, send away
if (!isset($_GET['token'])) {
	header('location:index.php');
	exit;
}

// Check that the hash actually exists
$hash = $_GET['token'];

$statement = $pdo->prepare("SELECT * FROM calendars WHERE token = :hash");
$statement->execute(array(
    "hash" => $hash,
));

if ($statement->rowCount() == 0) {
	// It doesn't exist...
    header('location:index.php');
    exit;
}