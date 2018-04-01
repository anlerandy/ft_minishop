<?php
function show_cat_all()
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

if (isset($_SESSION['level_user']) && $_SESSION['level_user'] === '1')
{
  $requete_get_cat = mysqli_prepare($db, "SELECT name, id FROM categories WHERE 1");
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
  echo "Il n'y a aucun élément à afficher...";
  return(0);
}
echo "
<html>
<head>
  <meta charset=\"UTF-8\" />
  <link rel=\"stylesheet\" type=\"text/css\" href=\"/css/default.css\" />
</head>
<body>";
    foreach($tab as $id => $item)
        echo "<a href=\"?categorie=".$id."\"><span>ID : ".$id." | </span><span>"." "."Nom de la catégorie : ".$item."</span></a><br/>";
  echo "</div>
</body>
</html>
";

}
