<?php
  $database = "if17_kippkert";
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