<?php
function show_us()
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
  $get_us = mysqli_prepare($db, "SELECT id, name, admin FROM users WHERE id = ?");
  mysqli_stmt_bind_param($get_us, "s", $_GET['user']);
  if (mysqli_stmt_execute($get_us))
  {
    mysqli_stmt_bind_result($get_us, $id, $name, $admin);
    while(mysqli_stmt_fetch($get_us))
    {
      	$tab[$name][] = $name;
				$tab[$name][] = $admin;
				$tab[$name][] = $id;
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
    {
        echo "<span>ID d'utilisateur : ".$val[2]."</span><br/><span>";
        if (isset($val[1]) && $val[1] === 1)
        {
          echo "<br/>Statut de l'utilisateur :<b style=\"margin-left:10px;\">Administrateur</b>";
        }
        echo"</span></a><br/>";
        if ($_SESSION['user_id'] !== $val[2])
      {
        echo "
      <form method=\"POST\" action=\"/includes/database/delete.php?users=".$val[2]."\">
      <button name=\"redirect\" value=\"".$_SERVER['PHP_SELF']."\">Supprimer l'utilisateur</button>
      </form>";}
      }
  echo "</div>
</body>
</html>
";

}
