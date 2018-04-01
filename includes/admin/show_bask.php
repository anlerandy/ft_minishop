<?php
function show_bask()
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
  $requete_get_bask = mysqli_prepare($db, "SELECT id, user, items, status FROM baskets WHERE 1");
  if (mysqli_stmt_execute($requete_get_bask))
  {
    mysqli_stmt_bind_result($requete_get_bask, $id, $name, $litem, $stats);
    while(mysqli_stmt_fetch($requete_get_bask))
    {
      $tab[$id][] = $id;
      $tab[$id][] = $name;
      $tab[$id][] = $litem;
      $tab[$id][] = $stats;
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
    foreach($tab as $id => $val)
        echo "<a href=\"#\"><span>ID du panier : ".$val[0]." | </span><span>"." "."ID de l'utilisateur : ".$val[1]."</span></a><br/>";
  echo "</div>
</body>
</html>
";

}
