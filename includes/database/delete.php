<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";
// Connect to the SQL server
$db = mysqli_connect($db_server, $db_user, $db_password);
if (!$db)
exit ("ERROR: " . mysqli_connect_error());
// Create the database if it doesn't exist already
if (!mysqli_select_db($db, $db_name))
header('Location: /includes/_install.php');

// Récupération des catégories.

if (isset($_SESSION['level_user']) && $_SESSION['level_user'] === '1' && isset($_GET))
{
  $id = sprintf("DELETE FROM  %s WHERE id = ?", key($_GET));
  if (!($requete_del = mysqli_prepare($db, $id)))
    exit (mysqli_error($db));
  mysqli_stmt_bind_param($requete_del, "s", $_GET[key($_GET)]);
  if (mysqli_stmt_execute($requete_del))
  {
    echo key($_GET)." : ".$_GET[key($_GET)]." a bien été supprimé.<br/>Redirection...";
  	header( "refresh:2;url=".$_POST['redirect']);
  }
  else
  $err = 1;
}
else
$npass = 1;
if (isset($npass))
{
  echo "Une erreur est survenue lors du chargement de ".key($_GET)."...";
  return(0);
}
?>
