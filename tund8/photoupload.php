<?php
  require("functions.php"); 

  $isImage = "";
  $notImage = "";
  $imageFileTypes = "";
  $fileExistsError = "";
  $fileSizeError = "";
  $uploadError = "";
  $uploadSuccess = "";
  $uploadUnsuccessful  = "";

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

  $target_dir = "../../img/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
          $isImage = "Tegemist on pildiga - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          $notImage = "Tegemist ei ole pildiga.";
          $uploadOk = 0;
      }
  }

  // Check if file already exists
  if (file_exists($target_file)) {
    $fileExistsError = "Pilt on juba üleslaetud.";
    $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 2000000) {
    $fileSizeError = "Sinu fail ületab lubatud suuruse piiri.";
    $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    $imageFileTypes = "Ainult JPG, JPEG, PNG & GIF failid on lubatud.";
    $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    $uploadError = "Tekkis viga, üleslaadimine ebaõnnestus.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $uploadSuccess = "Pilt ". basename($_FILES["fileToUpload"]["name"]). " on üleslaetud.";
    } else {
        $uploadUnsuccessful = "Faili üleslaadimine ebaõnnestus.";
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Kertu Kipper</title>
  </head>
  <body>
    <h1>Veebiprogrammeerimine</h1>
    <p>Veebileht on loodud veebiprogrammeerimise kursusel ning ei sisalda   tõsiseltvõetavat sisu.</p>
    <p><a href="main.php">Pealeht</a></p>
    <p><a href="?logout=1">Logi välja</a></p>
    <hr>
    <h2>Piltide üleslaadimine</h2>
    <form action="photoupload.php" method="post" enctype="multipart/form-data">
      Vali pilt üleslaadimiseks:
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Lisa pilt" name="submit">
      <br>
      <span><?php echo "\n", $imageFileTypes, $notImage, $isImage, $fileExistsError, $fileSizeError, $uploadError, $uploadSuccess, $uploadUnsuccessful; ?></span>
    </form>
  </body>
</html>