<?php

// Makes sure there is a token
require 'includes/beforeLink.php';

?>
<html>
	<head>
		<title>Din kalenderlänk</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script type="text/javascript">
			window.onload = function(e) {
				document.getElementById('link').select();
			};
		</script>
	</head>
	<body>
		<div class="form">
			<div class="row wide">
				<label>
					Din länk:
					<input readonly="readonly" id="link" type="text" name="link" value="<?php echo $root; ?>/calendar/?c=<?php echo $hash; ?>" />
				</label>
				<div class="clear"></div>
				<a href="index.php" class="back">Tillbaka</a>
			</div>
			<p class="center">
				Kopiera länken ovan och klistra in den i ditt kalenderprogram för att börja prenumerera på dina matcher!
			</p>
		</div>
	</body>
</html>