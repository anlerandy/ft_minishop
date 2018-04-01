<?php
function show_prod($column, $filter, $order)
{
  require $_SERVER["DOCUMENT_ROOT"] . "/includes/database/config.php";

  // Connect to the SQL server
  $db = mysqli_connect($db_server, $db_user, $db_password);
  if (!$db)
  exit ("ERROR: " . mysqli_connect_error());
  // Create the database if it doesn't exist already
  if (!mysqli_select_db($db, $db_name))
  header('Location: /includes/_install.php');

  // Récupération des Produits selon les filtres et ordres.
  if (isset($column) && isset($filter))
  {
    $filter = sprintf("SELECT * FROM items WHEN %s=%s", $column, $filter);
  }
  else
  {
    $filter = NULL;
    $column = NULL;
  }
  if (isset($order))
  {
    $order = sprintf("ORDER BY %s", $order);
    if (isset($filter) && isset($column))
    sprintf("%s %s", $filter, $column);
    else
    $filter = sprintf("SELECT * FROM items %s", $order);
  }
  else if (!isset($filter))
  $filter = "SELECT * FROM items";

  $requete_get_prod = mysqli_prepare($db, $filter);
  if (mysqli_stmt_execute($requete_get_prod))
  {
    mysqli_stmt_bind_result($requete_get_prod, $id, $name, $categories, $img, $price);
    while(mysqli_stmt_fetch($requete_get_prod))
    {
      if (!isset($img))
        $img = "https://abtsmoodle.org/abtslebanon.org/wp-content/uploads/2017/10/image_unavailable.jpg";
      $tab[$id] = [
        "name" => $name,
        "cat" => $categories,
        "img" => $img,
        "price" => $price];
    }
    if(!isset($tab))
    $npass = 1;
  }
  else
  $err = 1;
  if (isset($npass))
  {
    echo "Une erreur est survenue lors du chargement des produits...";
    return(0);
  }
  echo "
  <html>
  <head>
  <meta charset=\"UTF-8\" />
  <link rel=\"stylesheet\" type=\"text/css\" href=\"/css/default.css\" />
  </head>
  <body>
  <div  id=\"container\"><h2>Liste des articles :</h2>";
  foreach($tab as $id => $item)
    echo "<a href=\"?produit=\""
        .key(array_keys($tab)).
        "\"><img src=\""
        .$item['img'].
        "\"/><br/><b>"
        .$item['name'].
        "</b><br/>"
        .$item['price'].
        "€</a><br/>";
  echo "
  </div>
  </body>
  </html>
  ";

};
?>
