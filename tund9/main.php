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

  //mitu pilti on
  $fileCount = count($picFiles);
  $picNumber = mt_rand(0, $fileCount -1);
  $picToShow = $picFiles[$picNumber];

  require("header.php");
?>

<h2><?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?></h2>
<p><a href="usersinfo.php">Kasutajate info</a></p>
<p><a href="usersideas.php">Kasutajate ideed</a></p>
<p><a href="photoupload.php">Piltide üleslaadimine</a></p>
<h4>Üks suvaline pilt:</h4>
<img src="<?php echo $dirToRead .$picToShow?>" alt="Tallinna Ülikool">
<p><a href="?logout=1">Logi välja</a></p>
<?php 
require("footer.php");
?>