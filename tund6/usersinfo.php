<?php
  require("functions.php");
  //kui pole sisseloginud, siis sisselogimise lehele
  if(!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit();
  }

  //kui logib välja
  if (isset($_GET["logout"])) {
    //lõpetame sessiooni
    session_destroy();
    header("Location: login.php");
  }

  /*while($stmt->fetch()) {
    
  }
  */

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Kertu Kipper</title>
    <link rel="stylesheet" href="main.css">
  </head>
  <body>
      <h1>Veebiprogrammeerimine</h1>
      <p>Veebileht on loodud veebiprogrammeerimise kursusel ning ei sisalda   tõsiseltvõetavat sisu.</p>
      <h2>Unde impedit</h2>
      <p>Consequatur voluptatibus eligendi architecto. Odio dolores corrupti error. Vel amet quas vel totam repudiandae. Ducimus necessitatibus et repellat corporis eveniet doloremque tenetur.
      </p>
      <p><a href="main.php">Pealeht</a></p>
      <hr>
      <h2>Kõik süsteemi kasutajad</h2>
      <table border="1" style="border: 1px solid black; border-collapse: collapse">
      <tr>
        <th>Eesnimi</th>
        <th>Perekonnanimi</th>
        <th>Email</th>
      </tr>
      <tr>
        <td>Juku</td>
        <td>Mets</td>
        <td>juku@mets.ee</td>
      </tr>
      </table>
      <p><a href="?logout=1">Logi välja</a></p>

  </body>
</html>