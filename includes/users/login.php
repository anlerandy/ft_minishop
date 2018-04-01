<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="/css/default.css" />
</head>
<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/users.php";
// Connect to the SQL server
$db = mysqli_connect($db_server, $db_user, $db_password);
if (!$db)
exit ("ERROR: " . mysqli_connect_error());
// Create the database if it doesn't exist already
if (!mysqli_select_db($db, $db_name))
header('Location: /includes/_install.php');
if (isset($_SESSION['logged_in_user']))
header('Location: /');
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html";

// Récupération des valeurs passées en paramêtres. Et Création du Nouvel User.

if (isset($_POST['login']) && isset($_POST['pass']))
{
	if ($_POST['login'] !== "" && $_POST['pass'] !== "")
	{
		$pass = hash("sha512", $_POST['pass']);
		$requete_get_log = mysqli_prepare($db, "SELECT id, name, password, admin FROM users WHERE name=? AND password=?");
		mysqli_stmt_bind_param($requete_get_log, "ss", $_POST['login'], $pass);
		if (mysqli_stmt_execute($requete_get_log))
		{
			mysqli_stmt_bind_result($requete_get_log, $id, $name, $pass, $admin);
			while(mysqli_stmt_fetch($requete_get_log))
			{
				$tab[$pass][] = $name;
				$tab[$pass][] = $admin;
				$tab[$pass][] = $id;
			}
			if(!isset($tab))
			$npass = 1;
			else
			{
				$_SESSION['logged_in_user'] = $tab[$pass][0];
				$_SESSION['level_user'] = sprintf($tab[$pass][1]);
				$_SESSION['user_id'] = sprintf($tab[$pass][2]);
				header('Location: /');
			}
		}
		else
		$err = 1;
	}
	else
	$npass = 1;
}

?>
<body>
	<form id="log" method="POST" action="login.php">
		Connexion :<br/>


		<?php
		// Affiche Les erreurs liée aux connection.
		if (isset($npass))
		{
			echo "<br/>Le mot de passe ou l'utilisateur est incorrect.<br/>
			<style>
			#log input{background:firebrick;color:white;};
			</style>";
		}
		if (isset($err))
		{
			echo "<br/>Error: " . mysqli_error($query);
		}
		?>
		<br/>
		Votre login : <input autofocus name="login" placeholder="Votre login" value=
		<?php
		if (isset($_POST['login'])){echo $_POST['login'];}
		?>
		><br />
		Mot de passe: <input type="password" name="pass" placeholder="Votre mot de passe"><br />
		<button type="submit" name="submit" value="OK">Connexion</button>
	</form>
</body>
</html>
