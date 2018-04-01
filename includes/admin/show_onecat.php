<?php
function show_onecat()
{
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
  $requete_get_cat = mysqli_prepare($db, "SELECT name, id FROM categories WHERE id=?");
  mysqli_stmt_bind_param($requete_get_cat, "i", $_GET['categorie']);
  if (mysqli_stmt_execute($requete_get_cat))
  {
    mysqli_stmt_bind_result($requete_get_cat, $name, $id);
    while(mysqli_stmt_fetch($requete_get_cat))
    {
      $tab[$id] = $name;
    }
    if(!isset($tab))
    $npass = 1;
  }
  else
  $err = 1;
}
else
$npass = 1;
if (isset($npass))
{
  echo "Une erreur est survenue lors du chargement des catégories...";
  return(0);
}
echo "
<html>
<head>
  <meta charset=\"UTF-8\" />
  <link rel=\"stylesheet\" type=\"text/css\" href=\"/css/default.css\" />
</head>
<body>
  <div  id=\"container\">";
  if (isset($_GET['categorie']))
      echo "<h1>Modifier la catégorie : ".$name."</h1>";
  if(isset($tab))
      echo "<h4>ID de la catégorie : ".$id."</h4><br/>
  <form method=\"POST\" action=\"index.php?categories=".$id."\">
  <input placeholder=\"Nouveau nom de catégorie. P. Ex. : ".$name."\" name=\"newname\" />
  <button name=\"submit\" value=\"submited\">Nouveau nom</button>
  </form>
<form method=\"POST\" action=\"/includes/database/delete.php?categories=".$id."\">
<button name=\"redirect\" value=\"".$_SERVER['PHP_SELF']."\">Supprimer la catégorie</button>
</form>
    </div><hr/>
</body>
</html>
";

}
