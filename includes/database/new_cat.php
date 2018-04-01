<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/users.php";

// Vérification qu'un utilisateur est connecté ET qu'il est admin. Redirigé en index sinon.
if (isset($_SESSION['logged_in_user']))
{
  if (!isset($_SESSION['level_user']) || $_SESSION['level_user'] !== '1')
  header("Location: /");
}
else
header("Location: /");
// Connect to the SQL server
$db = mysqli_connect($db_server, $db_user, $db_password);
if (!$db)
exit ("ERROR: " . mysqli_connect_error());
// Create the database if it doesn't exist already
if (!mysqli_select_db($db, $db_name))
header('Location: /includes/_install.php');
?>
<html>
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="/css/default.css" />
</head>
<body>
  <?php
  require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/menu.html";

  // Petite vérification de ce qui se trouve dans nos deux arrays
  if (isset($_GET) && isset($_GET['name']) && $_GET['name'] !== "")
  {
    if (!($requete_ins = mysqli_prepare($db, "INSERT INTO categories (id, name) VALUES (null, ?)")))
      exit ("Err02".mysqli_error($db));
    mysqli_stmt_bind_param($requete_ins, "s", $_GET['name']);
    if (!mysqli_stmt_execute($requete_ins))
      $err01 = 1;
    else
    {
      $tmp = mysqli_prepare($db, "SELECT id FROM categories WHERE name=?");
      mysqli_stmt_bind_param($tmp, "s", $_GET['name']);
      if (mysqli_stmt_execute($tmp))
      {
        mysqli_stmt_bind_result($tmp, $id);
        while (mysqli_stmt_fetch($tmp))
        {
          $id = $id;
        }
      }
    header("Location: /includes/admin/?categorie=$id");
    }
  }
  ?>
  <div id="wrap">
    <div id="sidebar">
      Manage :<br/>
      <a href="/includes/admin?p=cat">Catégories -></a><br/>
      <a href="/includes/admin?p=prod">Produits -></a><br/>
      <a href="/includes/admin?p=user">Utilisateurs -></a><br/>
      <a href="/includes/admin?p=basket">Paniers -></a><br/>
    </div>
    <div id="frame">
      <div id="add_container">
        <form method="GET" action="new_cat.php">
          Name : <input name="name" />
          <button name="redirect" value="">Créer</button>
        </form>
        <?php if(isset($err01))
        {
        echo "Err01".mysqli_stmt_error($requete_ins);
      }?>
      </div>
    </div>
  </div>
</body>
</html>
