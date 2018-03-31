<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Erreur</title>
	</head>
	<body>
		Une erreur est survenue (<?php echo isset($_GET["error"]) ? $_GET["error"] : "unknown" ?>).
	</body>
</html>
