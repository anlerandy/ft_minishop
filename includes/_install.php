<html>
<body>
<?php
if (isset($_GET['install']))
{
	if ($_GET[install] == install)
		header('Location: /install.php');
	if ($_GET[install] === abort)
	{
		echo 'Site inexistant.';
		exit (0);
	}
}
?>
La base de donnée n'existe pas, voulez-vous la créer ?
<form method="get" action="_install.php">
<button name="install" value="install">Oui</button>
<button name="install" value="abort">Non</button>
</form>
</body>
</html>
