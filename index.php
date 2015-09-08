<?php

// Includes the file that handles the request of submission of the form
require 'includes/handleSubmit.php';

?>
<html>
	<head>
		<title>Exportera Fogiskalender</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<form method="post" action="index.php">
			<div class="form">
				<p class="center">Fyll i dina Fogis-uppgifter nedan för att få en länk som kan importeras i valfri kalender (tex Google Calendar) med dina kommande matcher. Fungerar endast för domare.</p>
				<div class="row">
					<label>
						Ditt användarnamn i Fogis:
						<input type="text" name="username" />
					</label>
					<div class="clear"></div>
				</div>
				<div class="row">
					<label>
						Ditt lösenord i Fogis:
						<input type="password" name="password" />
					</label>
					<div class="clear"></div>
				</div>
				<div class="row">
					<input type="submit" value="Generera kalender-länk" />
				</div>
				<p class="center responsibility">
					När du klickar på knappen ovan kommer ditt användarnamn och ditt lösenord sparas. Lösenordet kommer krypteras under lagringen för att inte kunna läsas utifrån. Dock rekommenderas att du har ett unikt lösenord till ditt Fogis-konto, ifall något skulle hända. Det är aldrig bra att ha samma lösenord på flera ställen. Jag friskriver mig också från allt ansvar och du använder tjänsten som den är. Kolla regelbundet Fogis så att du inte missar någon match. Jag tar väldigt gärna emot synpunkter och feedback på <a href="mailto:jonas@dahl.guru">jonas@dahl.guru</a>. Källkoden finns givetvis på <a href="http://github.com/jonasdahl">GitHub</a>.<br />
					<br />
					<span style="float: right"><a href="http://dahl.guru">Jonas Dahl</a></span>
				</p>
			</div>
		</form>
	</body>
</html>