<?php
function show_users()
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
  $get_users = mysqli_prepare($db, "SELECT id, name, admin FROM users WHERE 1");
  if (mysqli_stmt_execute($get_users))
  {
    mysqli_stmt_bind_result($get_users, $id, $name, $admin);
    while(mysqli_stmt_fetch($get_users))
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
        echo "<a href=\"?user=".$val[2]."\"><span>ID : ".$val[2]." | </span><span>"." "."Nom de l'utilisateur : ".$id;
        if (isset($val[1]) && $val[1] === 1)
        {
          echo "<b style=\"margin-left:10px;\">Administrateur</b>";
        }
        echo"</span></a><br/>";
      }
  echo "</div>
</body>
</html>
";

}
