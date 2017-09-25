<?php
  require("../../../config.php");

  $signupFirstName = "";
  $signupFamilyName = "";
  $signupEmail = "";
  $gender = "";
  $signupPassword = "";
  $signupBirthDay = null;
  $signupBirthMonth = null;
  $signupBirthYear = null;
  $signupBirthDate = "";
  
  $loginEmail = "";

  $signupFirstNameError = "";
  $signupFamilyNameError = "";
  $signupEmailError = "";
  $genderError = "";
  $signupPasswordError = "";
  $signupBirthDayError = null;
  $signupBirthMonthError = null;
  $signupBirthYearError = null;
  
	
	//kas on kasutajanimi sisestatud
	if (isset ($_POST["loginEmail"])){
		if (empty ($_POST["loginEmail"])){
			$loginEmailError ="NB! Ilma selleta ei saa sisse logida!";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}
	
	//kontrollime, kas kirjutati eesnimi
	if (isset ($_POST["signupFirstName"])){
		if (empty ($_POST["signupFirstName"])){
			$signupFirstNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFirstName = $_POST["signupFirstName"];
		}
	}
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset ($_POST["signupFamilyName"])){
		if (empty ($_POST["signupFamilyName"])){
			$signupFamilyNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFamilyName = $_POST["signupFamilyName"];
		}
  }

  //kas sünnikuupäev on sisestatud
  if (isset ($_POST["signupBirthDay"])){
		$signupBirthDay = $_POST["signupBirthDay"];
		//echo $signupBirthDay;
	}
  
  //kas sünnikuu on sisestatud
  if ( isset($_POST["signupBirthMonth"]) ) {
    $signupBirthMonth = intval($_POST["signupBirthMonth"]);
  }

  //kas sünniaasta on sisestatud
  if (isset ($_POST["signupBirthYear"])){
		$signupBirthYear = $_POST["signupBirthYear"];
		//echo $signupBirthYear;
  }
  
  //kui sünnikuupäev sisestatud, siis kontrollime, kas kehtib
  if (isset ($_POST["signupBirthDay"]) and isset($_POST["signupBirthMonth"]) and isset($_POST["signupBirthYear"])) {
    if (checkdate(intval($_POST["signupBirthMonth"]), intval($_POST["signupBirthDay"]), intval($_POST["signupBirthYear"]))) {
      $birthDate = date_create($_POST["signupBirthMonth"] ."/" .$_POST["signupBirthDay"] ."/" .$_POST["signupBirthYear"]);
      $signupBirthDate = date_format($birthDate, "Y-m-d");
    } else {
        $signupBirthDayError = "Viga sünnikuupäeva sisestamisel";
    }
  }
   
	//kontrollime, kas kirjutati kasutajanimeks email
	if (isset ($_POST["signupEmail"])){
		if (empty ($_POST["signupEmail"])){
			$signupEmailError ="NB! Väli on kohustuslik!";
		} else {
			$signupEmail = $_POST["signupEmail"];
		}
	}
	
	if (isset ($_POST["signupPassword"])){
		if (empty ($_POST["signupPassword"])){
			$signupPasswordError = "NB! Väli on kohustuslik!";
		} else {
			//polnud tühi
			if (strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "NB! Liiga lühike salasõna, vaja vähemalt 8 tähemärki!";
			}
		}
	}
	
	if (isset($_POST["gender"]) && !empty($_POST["gender"])){ //kui on määratud ja pole tühi
			$gender = intval($_POST["gender"]);
		} else {
			$signupGenderError = " (Palun vali sobiv!) Määramata!";
    }

  //UUE KASUTAJA ANDMEBAASI KIRJUTAMINE, kui kõik on olemas 
  if (empty($signupFirstNameError) and empty($signupFamilyNameError) and empty($signupBirthDayError) and empty($genderError) and empty($signupEmailError) and empty ($signupPasswordError )) {
    // echo "hakkan salvestama";
    //krüpteerin parooli
    $signupPassword = hash("sha512", $_POST["signupPassword"]);
    //echo "\n Parooli " .$_POST["signupPassword"] ."räsi" . $signupPassword;
    //loome andmebaasi ühenduse
    $database = "if17_kippkert";
    $mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
    //valmistame ette käsu andmebaasiserverile
    $stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    echo $mysqli->error;
    //s - strin
    //i - integer ehk täisarv
    //d - decimal ehk murdarvud
    $stmt->bind_param("sssiss", $signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
    //$stmnt->execute ();
    if ($stmt->execute()) {
      echo "\n Õnnestus";
    } else {
        echo "\n tekkis viga: " .$stmt->error;
    }
    $stmt->close();
    $mysqli->close();
  }

  //loome kuupäeva valiku
  $signupDaySelectHTML = "";
  $signupDaySelectHTML .= '<select name="signupBirthDay">' ."\n";
  $signupDaySelectHTML .= '<option value="" selected disabled>kuupäev</option>' ."\n";
  for ($i = 1; $i < 32; $i ++){
    if($i == $signupBirthDay){
      $signupDaySelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
    } else {
      $signupDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
    } 
  }
  $signupDaySelectHTML.= "</select> \n";
  
  //loome sünnikuu valiku
  $signupMonthSelectHTML = "";
  $monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  $signupMonthSelectHTML .= '<select name="signupBirthMonth">' ."\n";
  $signupMonthSelectHTML .= '<option value="" selected disabled>kuu</option>' ."\n";
  foreach ($monthNamesEt as $key=>$month) {
    if ($key + 1 === $signupBirthMonth) {
      $signupMonthSelectHTML .= '<option value="' .($key + 1) .'" selected>' .$month .'</option>' ."\n"; 
    } else {
    $signupMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month .'</option>' ."\n"; 
    }
  }
  $signupMonthSelectHTML .= "</select> \n";

  //loome aasta valiku
  $signupYearSelectHTML = "";
  $signupYearSelectHTML .= '<select name="signupBirthYear">' ."\n";
  $signupYearSelectHTML .= '<option value="" selected disabled>aasta</option>' ."\n";
  $yearNow = date("Y");
  for ($i = $yearNow; $i > 1900; $i --){
    if($i == $signupBirthYear){
      $signupYearSelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
    } else {
      $signupYearSelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
    }
    
  }
  $signupYearSelectHTML.= "</select> \n";
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Sisselogimine või uue kasutaja loomine</title>
</head>
<body>
	<h1>Logi sisse</h1>
	<p>Sisselogimise harjutamine.</p>
	
	<form method="POST">
		<label>Kasutajanimi (E-post): </label>
		<input name="loginEmail" type="email" value="<?php echo $loginEmail; ?>">
		<br><br>
		<input name="loginPassword" placeholder="Salasõna" type="password">
		<br><br>
		<input type="submit" value="Logi sisse">
	</form>
	
	<h1>Loo kasutaja</h1>
	<form method="POST">
		<label>Eesnimi </label>
		<input name="signupFirstName" type="text" value="<?php echo $signupFirstName; ?>">
    <span><?php echo $signupFirstNameError; ?></span>
		<br>
		<label>Perekonnanimi </label>
		<input name="signupFamilyName" type="text" value="<?php echo $signupFamilyName; ?>">
    <span><?php echo $signupFamilyNameError; ?></span>
    <br><br>
    <label>Sisesta oma sünnikuupäev</label>
    <?php
      echo $signupDaySelectHTML .$signupMonthSelectHTML .$signupYearSelectHTML;
    ?>
    <span><?php echo $signupBirthDayError; ?></span>
		<br><br>
		<label>Sugu</label><span>
		<br>
		<input type="radio" name="gender" value="1" <?php if ($gender == '1') {echo 'checked';} ?>><label>Mees</label> <!-- Kõik läbi POST'i on string!!! -->
		<input type="radio" name="gender" value="2" <?php if ($gender == '2') {echo 'checked';} ?>><label>Naine</label>
		<br><br>
		
		<label>Kasutajanimi (E-post)</label>
		<input name="signupEmail" type="email" value="<?php echo $signupEmail; ?>">
    <span><?php echo $signupEmailError; ?></span>
		<br><br>
		<input name="signupPassword" placeholder="Salasõna" type="password">
    <span><?php echo $signupPasswordError; ?></span>
		<br><br>

		
		<input type="submit" value="Loo kasutaja">
	</form>
		
</body>
</html>