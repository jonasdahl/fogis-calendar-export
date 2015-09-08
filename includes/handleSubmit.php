<?php

include('database.php');

/**
 * A function that generates a hash that is not already 
 * in use. Adds letters to username and password until 
 * something unique is created.
 */
function generateToken($pdo, $username, $password) {
	$hash = hash('ripemd160', $username . $password);
	$sql = "SELECT COUNT(*) FROM calendars WHERE token = :hash";
	$result = $pdo->prepare($sql);
	$result->execute(array('hash' => $hash));
	if ($result->fetchColumn() > 0)
		return generateToken($pdo, $username . 'a', $password . 'b');
	return $hash;
}

// Only if username and password has been sent we need to proceed
if (isset($_POST['username']) && isset($_POST['password'])) {
	// Check if there already is a token for this credentials
	$statement = $pdo->prepare("SELECT * FROM calendars WHERE username = :username AND password = :password");
	$statement->execute(array(
	    "username" => $_POST['username'],
	    "password" => @openssl_encrypt($_POST['password'], $method, $key),
	));

	// If there already exists an entry in the database, its not necessary to create a new
	// Send direct to link page
	if ($statement->rowCount() > 0) {
	    header('location:link.php?token=' . $statement->fetch(PDO::FETCH_ASSOC)['token']);
	    exit;
	}

	// Generate the token
	$hash = generateToken($pdo, $_POST['username'], $_POST['password']);

	// And insert it
	$statement = $pdo->prepare("INSERT INTO calendars(token, username, password) VALUES(:token, :username, :password)");
	$statement->execute(array(
		"token" => $hash,
	    "username" => $_POST['username'],
	    "password" => @openssl_encrypt($_POST['password'], $method, $key),
	));
	
	// Send user to link page after insert
	header('location:link.php?token=' . $hash);
	exit;
}