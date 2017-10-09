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
  $dirToRead = "../../img/";
  //kuna tahan pildifaile, siis filtreerin
  $picFileTypes = ["jpg", "jpeg", "png", "gif"];
  $picFiles = [];
  // $allFiles = scandir($dirToRead);
  //loen kataloogi ja viskan kaks esimest masiivi liiget (. ja ..) välja
  $allFiles = array_slice(scandir($dirToRead),2);
  //var_dump($allFiles);
  //tsükkel mis töötab ainult massiividega 
  foreach ($allFiles as $file){
    $fileType = pathinfo ($file, PATHINFO_EXTENSION);
    //kas see tüüp on lubatud nimekirjas
    if (in_array($fileType, $picFileTypes) == true){
      array_push($picFiles, $file);
    }
  }
  var_dump($picFiles);
  //mitu pilti on
  $fileCount = count($picFiles);
  $picNumber = mt_rand(0, $fileCount -1);
  $picToShow = $picFiles[$picNumber];
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
      <p><a href="usersinfo.php">Kasutajate info</a></p>
      <p><a href="usersideas.php">Kasutajate ideed</a></p>
      <p>Üks pilt Tallinna Ülikooli koridorist.</p>
      <img src="<?php echo $dirToRead .$picToShow?>" alt="Tallinna Ülikool">
      <p><a href="?logout=1">Logi välja</a></p>
  </body>
</html>