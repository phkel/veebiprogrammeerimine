<?php
  require("../../../config.php");
  $database = "if17_kippkert";
// alustan sessiooni
  session_start();
//sisselogimise funktsioon
  function signIn($email, $password){
    $notice = "";
		//ühendus serveriga
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, password FROM vpusers WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $firstnameFromDb, $lastnameFromDb, $emailFromDb, $passwordFromDb);
    $stmt->execute();
    
    //kontrollime vastavust
    if ($stmt->fetch()) {
      $hash = hash("sha512", $password);
      if ($hash == $passwordFromDb) {
        $notice = "Logisite sisse";
        
        // määran sessiooni muutujad 
        $_SESSION["userId"] = $id;
        $_SESSION["userEmail"] = $emailFromDb;
        $_SESSION["firstname"] = $firstnameFromDb;
				$_SESSION["lastname"] = $lastnameFromDb;
        
        //liigume edasi pealehele (main.php)
        header("Location: main.php");
        exit();
      } else {
          $notice = "Vale salasõna";
      }
    } else {
        $notice = 'Sellise kasutajatunnusega "' .$email .'" pole kasutajat';
    }
    $stmt->close();
    $mysqli->close();
    return $notice;
  }
//kasutaja salvestamise funktsioon
  function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword) {
    //loome andmebaasi ühenduse
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //valmistame ette käsu andmebaasiserverile
    $stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthday, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
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
//mõtete salvestamine
  function saveIdea($idea, $color){
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("INSERT INTO vpuserideas (userid, idea, ideacolor) VALUES (?, ?, ?)");
    echo $mysqli->error;
    $stmt->bind_param("iss", $_SESSION["userId"], $idea, $color);
    if($stmt->execute()){
      $notice = "Mõte on salvestatud";
    } else {
        $notice = "Mõtte salvestamisel tekkis viga: " .$stmt->error;
    }
    $stmt->close();
    $mysqli->close();
    return $notice;
  }

//Kõikide ideede lugemise funktsioon
  function readAllIdeas() {
    $ideasHTML = "";
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //$stmt = $mysqli->prepare("SELECT idea, ideaColor FROM vpuserideas WHERE userid = ?");
    $stmt = $mysqli->prepare("SELECT id, idea, ideaColor FROM vpuserideas WHERE userid = ? ORDER BY id DESC");
    $stmt->bind_param("i", $_SESSION["userId"]);
    $stmt->bind_result($ideaId, $idea, $color);
    $stmt->execute();
    //$result = array();
    while ($stmt->fetch()) {
      $ideasHTML .= '<p style="background-color: ' .$color .'">' .$idea  .' | <a href="ideaedit.php?id='.$ideaId .'">Toimeta</a>' ."</p> \n";
      //link: <a href="ideaedit.php?id=4"> Toimeta </a>
    }
    $stmt->close();
    $mysqli->close();
    return $ideasHTML;
  }

//uusima idee lugemine
  function latestIdea(){
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    // $stmt = $mysqli->prepare("SELECT @last_id := MAX(id) FROM vpuserideas"); 
    // $stmt->bind_result($last_id);
    // $stmt->close();
    $stmt = $mysqli->prepare("SELECT idea FROM vpuserideas WHERE id = (SELECT MAX(id) FROM vpuserideas)");
    //$stmt->bind_param("i", $last_id);
    $stmt->bind_result($idea);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $mysqli->close();
    return $idea;
  }

//genderi muutmine
  function genderLabel($genderID) { 
    $label = "";
    if ($genderID === 2) {
      $label = "naine";
    } else {
      $label = "mees";
    }
    return $label;
  }

//kasutaja tabeli sisu 
  function fetchAllUserinfo() {
    $html = "";
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //$stmt = $mysqli->prepare("SELECT idea, ideaColor FROM vpuserideas WHERE userid = ?");
    $stmt = $mysqli->prepare("SELECT firstname, lastname, email, birthday, gender FROM vpusers");
    // $stmt->bind_param("sssis", $signupFirstName, $signupFamilyName, $signupEmail,$signupBirthDate, $gender);
    $stmt->bind_result($signupFirstName, $signupFamilyName, $signupEmail,$signupBirthDate, $gender);
    $stmt->execute();
    //$result = array();
    while ($stmt->fetch()) {
      $html .= '<tr>'
          .'<td>'.$signupFirstName .'</td>'
          .'<td>'.$signupFamilyName.'</td>'
          .'<td>'.$signupEmail.'</td>'
          .'<td>'.$signupBirthDate.'</td>'
          .'<td>'.genderLabel($gender).'</td>'
        .'</tr>'; 
    }
    $stmt->close();
    $mysqli->close();
    return $html;
  }
  
  //pildi salvestamine
  function saveImage($target_file, $target_file_thumb, $visibility) {
    //loome andmebaasi ühenduse
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //valmistame ette käsu andmebaasiserverile
    $stmt = $mysqli->prepare("INSERT INTO vpphotos (userid, filename, thumbnail, visibility VALUES (?, ?, ?, ?)");
    echo $mysqli->error;
    $stmt->bind_param("issi", $_SESSION["userId"], $target_file, $target_file_thumb, $visibility);
    if($stmt->execute()){
      $notice = "Pilt on salvestatud";
    } else {
      $notice = "Pildi salvestamisel tekkis viga: " .$stmt->error;
    }
    $stmt->close();
    $mysqli->close();
    return $notice;
    } 

//sisestuse kontrollimise funktsioon
  function test_input($data) {
    $data = trim($data); //ebavajalikud tühikud eemaldatakse
    $data = stripslashes($data); //kaldkriipsud jms eelmadatakse
    $data = htmlspecialchars($data); //keelatud sümbolid eemaldatakse
    return $data;
  }
  // $x = 5;
  // $y = 6;
  // addValues ();
  // function addValues() {
  //   $z = $GLOBALS["x"] + $GLOBALS["y"];
  //   echo "Summa on: " .$z;
  // }
?>