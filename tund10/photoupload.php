<?php
  require("functions.php"); 
  $notice = "";

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
	
	//liidan klassi
	require("classes/Photoupload.class.php");
	//loome objekti
	// $myPhoto = new Photoupload("peidus");
	// echo $myPhoto->publicTest;
	// echo $myPhoto->privateTest;
	// loome objekti (ajutine fail, failitüüp)
	// $myPhoto = new Photouload($_FILES["fileToUpload"]["tmp_name"])
	// $fileType

  $target_dir = "../../img/";
  $target_dir_thumb = "../../thumbs/";
  $target_file = "";
  $uploadOk = 1;
	$visibility = "";
	$visibilityError = "";
	$imageFileType;
	$maxWidth = 600;
	$maxHeight = 400;
	$thumbWidth = 100;
	$thumbHeight = 100;
	$marginBottom = 10;
	$marginRight = 10;
  
  // Check if image file is a actual image or fake image
  if(isset($_POST["imageBtn"])) {
	// kas mingi fail valiti
	if(!empty($_FILES["fileToUpload"]["name"])) {

		$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
		// tekitame failinime koos ajatempliga
		$target_file = "hmv_" . (microtime(1) * 10000) . "." . $imageFileType;
		$target_file_thumb = "hmv_" . (microtime(1) * 10000) . "." . $imageFileType;
		
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$notice .= "Tegemist on pildiga - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
				$notice .= "Tegemist ei ole pildiga.";
				$uploadOk = 0;
			}

		// Kas selline pilt on üleslaetud
		if (file_exists($target_file)) {
			$notice .= "Pilt on juba üleslaetud.";
			$uploadOk = 0;
		}

		// Luba kindlaid faililaiendeid
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$notice .= "Ainult JPG, JPEG, PNG & GIF failid on lubatud.";
			$uploadOk = 0;
		}

		if ($uploadOk == 0) {
			$notice .= "Tekkis viga, üleslaadimine ebaõnnestus.";
		// kui saab laadida
		} else {

			//visibility error
			if (isset($_POST["visibility"]) && !empty($_POST["visibility"])){ //kui on määratud ja pole tühi
				$visibility = intval($_POST["visibility"]);
			} else {
				$visibilityError = " (Palun vali sobiv!) Määramata!";
			}

			//kasutan klassi
			$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
			$myPhoto->readExif();
			$myPhoto->resizeImage($maxWidth, $maxHeight);
			$myPhoto->addWatermark();
			// $myPhoto->addTextWatermark($myPhoto->exifToImage);
			$myPhoto->addTextWatermark("hmv_foto");
			$myPhoto->savePhoto($target_dir, $target_file);
			$myPhoto->clearImages();
			unset($myPhoto); 
		}

	} else {
		$notice .= "Palun vali pildifail.";
	} //kas üldse mõni fail valiti, lõppeb
	}

	//pildi salvestamine
  if(isset($_POST["imageBtn"])) {
		if(isset($_POST["fileToUpload"]) and isset($_POST["visibility"]) and !empty($_POST["fileToUpload"]) and !empty($_POST["visibility"])){
			$notice = saveImage($_POST["target_file"], $_POST["target_file_thumb"], $_POST["visibility"]);
		}
	}

	require("header.php");
?>

<p><a href="main.php">Pealeht</a></p>
<p><a href="?logout=1">Logi välja</a></p>
<hr>
<h2>Piltide üleslaadimine</h2>
<form action="photoupload.php" method="post" enctype="multipart/form-data">
	<input type="radio" name="visibility" value="1" <?php if ($visibility == '1') {echo 'checked';} ?>><label>Avalik</label> <!-- Kõik läbi POST'i on string!!! --> 
	<br>
	<input type="radio" name="visibility" value="2" <?php if ($visibility == '2') {echo 'checked';} ?>><label>Sisseloginud kasutajatele</label>
	<br>
	<input type="radio" name="visibility" value="3" <?php if ($visibility == '3') {echo 'checked';} ?>><label>Ainult omanikule</label>
	<span><?php echo $visibilityError; ?></span>
	<br> 
	Vali pilt üleslaadimiseks:
	<input type="file" name="fileToUpload" id="fileToUpload">
	<input type="submit" value="Lisa pilt" name="imageBtn" id="submitPhoto">
	<br>
</form>
<span><?php echo $notice; ?></span>

<?php 
	echo '<script type="text/javascript" src="javascript/checkFileSize.js"></script>';
	require("footer.php");
?>