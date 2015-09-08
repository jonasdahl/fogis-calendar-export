<?php

require_once '../../../../credentials.php';
require '../includes/database.php';
require '../classes/Request.php';
require '../classes/CalendarEvent.php';

// If there is no identification, leave
if (!isset($_GET['c'])) {
	header('location:../');
	exit;
}

// We should check that there actually is a hash like this
$sql = "SELECT * FROM calendars WHERE token = :hash";
$result = $pdo->prepare($sql);
$result->execute(array('hash' => $_GET['c']));
if ($result->rowCount() == 0) {
	header('location:../');
	exit;
}

// If there was a login for this one, get the info
$info = $result->fetch();

// Set some timezones and headers
date_default_timezone_set('Europe/Stockholm');
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . 'cal.ics');

// Create the request, send it and parse it
$request = new Request;
$request->loginGetUrl = 'https://fogis.svenskfotboll.se/Fogisdomarklient/Login/Login.aspx';
$request->loginPostUrl = 'https://fogis.svenskfotboll.se/Fogisdomarklient/Login/Login.aspx';
$request->finalGetUrl = 'https://fogis.svenskfotboll.se/Fogisdomarklient/Uppdrag/UppdragUppdragLista.aspx';
$request->username = $info['username'];
$request->password = openssl_decrypt($info['password'], $method, $key);
$request->send();
$request->parseResult();

// Create the output!
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Fogis Domare//Jonas Dahl//SV
URL:http://jdahl.se/domarkalender
NAME:Fogis Domare
X-WR-CALNAME:Fogis Domare
DESCRIPTION:Matcher för Jonas Dahl
X-WR-CALDESC:Matcher för Jonas Dahl
TIMEZONE-ID:Europe/Stockholm
X-WR-TIMEZONE:Europe/Stockholm
REFRESH-INTERVAL;VALUE=DURATION:PT12H
X-PUBLISHED-TTL:PT12H
CALSCALE:GREGORIAN
METHOD:PUBLISH

<?php

// This simple foreach outputs all the events
foreach ($request->events as $event) {
	echo $event->getIcs();
	echo "\r\n";
}

?>
END:VCALENDAR