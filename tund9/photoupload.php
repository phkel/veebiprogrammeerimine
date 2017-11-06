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

  $target_dir = "../../img/";
  $target_file = "";
  $uploadOk = 1;
	$imageFileType;
	$maxWidth = 600;
	$maxHeight = 400;
	$marginBottom = 10;
	$marginRight = 10;
  
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {

		// kas mingi fail valiti
		if(!empty($_FILES["fileToUpload"]["name"])) {

			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
			// tekitame failinime koos ajatempliga
			$target_file = $target_dir . pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] . "_" . (microtime(1) * 10000) . "." . $imageFileType;

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

			// Failisuuruse kontroll
			if ($_FILES["fileToUpload"]["size"] > 2000000) {
				$notice .= "Sinu fail ületab lubatud suuruse piiri.";
				$uploadOk = 0;
			}

			// Luba kindlaid faililaiendeid
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$notice .= "Ainult JPG, JPEG, PNG & GIF failid on lubatud.";
				$uploadOk = 0;
			}

			// Kas saab laadida?
			/*if ($uploadOk == 0) {
				$notice .= "Tekkis viga, üleslaadimine ebaõnnestus.";

			// Kui saab laadida
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$notice .= "Pilt ". basename($_FILES["fileToUpload"]["name"]). " on üleslaetud.";
				} else {
						$notice .= "Faili üleslaadimine ebaõnnestus.";
				}
			} */

			if ($uploadOk == 0) {
				$notice .= "Tekkis viga, üleslaadimine ebaõnnestus.";
			// kui saab laadida
			} else {

				//loeme EXIF infot, millal pilt tehti
				@$exif = exif_read_data($_FILES["fileToUpload"]["tmp_name"], "ANY_TAG", 0, true);
				// var_dump($exif);
				if(!empty($exif["DateTimeOriginal"])) {
					$textToImage = "Pilt tehti: " . $exif["DateTimeOriginal"];
				} else {
						$textToImage = "Pildistamise aeg teadmata.";
				}
				//lähtudes failitüübist loon sobiva pildiobjekti
				if($imageFileType == "jpg" or $imageFileType == "jpeg") {
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png") {
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif") {
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}

				//suuruse muutmine, küsime originaalsuurust
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				$sizeRatio = 1;
				if($imageWidth > $imageHeight) {
					$sizeRatio = $imageWidth / $maxWidth;
				} else {
					$sizeRatio = $imageHeight / $maxHeight;
				}
				$myImage = resize_image($myTempImage, $imageWidth, $imageHeight, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));

				//vesimärgi lisamine
				$stamp = imagecreatefrompng("../../graphics/hmv_logo.png");
				$stampWidth = imagesx($stamp);
				$stampHeight = imagesy($stamp);
				$stampPosX = round($imageWidth / $sizeRatio) - $stampWidth - $marginRight;
				$stampPosY = round($imageHeight / $sizeRatio) - $stampHeight - $marginBottom;
				imageCopy($myImage, $stamp, $stampPosX, $stampPosY, 0, 0, $stampWidth, $stampHeight);
				
				///lisame ka teksti vesimärgina
				$textColor = imagecolorallocatealpha($myImage, 200, 200, 200, 10);
				imagettftext ($myImage, 20, 0, 10, 25, $textColor, "../../graphics/HelveticaNeue.dfont", $textToImage);

				//salvestame pildi
				if($imageFileType == "jpg" or $imageFileType == "jpeg") {
					if(imagejpeg($myImage, $target_file, 95)){
						$notice = "Fail" . basename($_FILES["fileToUpload"]["name"]) . " on üleslaetud.";
					} else {
						$notice .= "Faili üleslaadimine ebaõnnestus.";
					}
				}

				if($imageFileType == "png") {
					if(imagejpeg($myImage, $target_file, 95)){
						$notice = "Fail" . basename($_FILES["fileToUpload"]["name"]) . " on üleslaetud.";
					} else {
						$notice .= "Faili üleslaadimine ebaõnnestus.";
					}
				}

				if($imageFileType == "gif") {
					if(imagejpeg($myImage, $target_file, 95)){
						$notice = "Fail" . basename($_FILES["fileToUpload"]["name"]) . " on üleslaetud.";
					} else {
						$notice .= "Faili üleslaadimine ebaõnnestus.";
					}
				}

				//mälu vabastamine
				imagedestroy($myImage);
				imagedestroy($myTempImage);

			}

		} else {
			 $notice .= "Palun vali pildifail.";
		} //kas üldse mõni fail valiti, lõppeb
	}

	function resize_image($image, $origW, $origH, $w, $h) {
		$dst = imagecreatetruecolor($w, $h);
		imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
		return $dst;
	}

	require("header.php");
?>

<p><a href="main.php">Pealeht</a></p>
<p><a href="?logout=1">Logi välja</a></p>
<hr>
<h2>Piltide üleslaadimine</h2>
<form action="photoupload.php" method="post" enctype="multipart/form-data">
	Vali pilt üleslaadimiseks:
	<input type="file" name="fileToUpload" id="fileToUpload">
	<input type="submit" value="Lisa pilt" name="submit">
	<br>
	<span><?php echo $notice; ?></span>
</form>
<?php 
	require("footer.php");
?>