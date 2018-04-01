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

if (isset($_POST['login']) && isset($_POST['pass']) && isset($_POST['npass']))
{
	if ($_POST['login'] !== "" && $_POST['pass'] !== "" && $_POST['npass'] !== "")
	{
		if ($_POST['pass'] == $_POST['npass'])
		{
			$pass = hash("sha512", $_POST['pass']);
			$requete_new_user = mysqli_prepare($db, "INSERT INTO users (id, name, password, admin) VALUES (null, ?, ?, '0')");
			mysqli_stmt_bind_param($requete_new_user, "ss", $_POST['login'], $pass);
			mysqli_stmt_execute($requete_new_user);
			if (mysqli_stmt_error($requete_new_user))
				$err = -1;
			else
			{
				$requete_get_log = mysqli_prepare($db, "SELECT id, name FROM users WHERE name=?");
				mysqli_stmt_bind_param($requete_get_log, "s", $_POST['login']);
				if (mysqli_stmt_execute($requete_get_log))
				{
					mysqli_stmt_bind_result($requete_get_log, $id, $name);
					while(mysqli_stmt_fetch($requete_get_log))
					{
						$tab[$name][] = $id;
					}
				$_SESSION['logged_in_user'] = $_POST['login'];
				$_SESSION['level_user'] = 0;
				$_SESSION['user_id'] = $id;
				mysqli_stmt_close($requete_new_user);
				mysqli_close($db);
				header('Location: /');
				}
			}
		}
	}
	else
	{
		$npass = -1;
	}
}

?>
<body>
	<form id="log" method="POST" action="signin.php">
	Inscription :<br/>


<?php
// Affiche Les erreurs liée aux connection.
if (isset($npass))
{
	echo "<br/>Tapez exactement le même mot de passe.";
}
if (isset($err))
{
	echo "<br/>Error: " . mysqli_stmt_error($requete_new_user);
}
?>
<br/>
	Votre login : <input name="login" placeholder="Votre login" value=
<?php
if (isset($_POST['login'])){echo $_POST['login'];}
?>
><br />
	Mot de passe: <input type="password" name="pass" placeholder="Votre mot de passe"><br />
	Confirmer le mot de passe: <input type="password" name="npass" placeholder="retapez votre mot de passe"><br />
	<button type="submit" name="submit" value="OK">Inscrition</button>
	</form>
</body>
</html>
