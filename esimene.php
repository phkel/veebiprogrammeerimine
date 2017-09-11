<?php
  //Muutujad
  $myName = "Kertu";
  $myFamilyName = "Kipper";
  $practiceStarted = "2017-09-11 8.15";

  // echo strtotime($practiceStarted);
  // echo strtotime("now");
  // $timePassed = strtotime("now") - strtotime($practiceStarted);
  // echo $timePassed;

  $hourNow = date("H");
  $partOfDay = "";

  if ($hourNow < 8) {
    $partOfDay = "varane hommik";
  }
  if ($horNow >= 8) {
    $partOfDay = "koolipäev";
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Kertu Kipper</title>
    <link rel="stylesheet" href="main.css">
  </head>
  <body>
      <div class="container">
        <h1>Veebiprogrammeerimine</h1>
        <p>Veebileht on loodud veebiprogrammeerimise kursusel ning ei sisalda   tõsiseltvõetavat sisu.</p>
        <h2>Unde impedit</h2>
        <p>Consequatur voluptatibus eligendi architecto. Odio dolores corrupti error. Vel amet quas vel totam repudiandae. Ducimus necessitatibus et repellat corporis eveniet doloremque tenetur.
        </p>
        <p>
        Et molestiae accusantium aut impedit et nam cumque non. Temporibus enim veniam quo. Iste qui vel tenetur sed optio placeat officia officia. Natus sapiente dolorem perferendis repellendus. Voluptatem cupiditate perferendis eum sed recusandae doloremque est. Nihil vel dolores temporibus voluptas est veniam fuga consequuntur.
        </p>
        <?php
          echo "<p>Täna on ilus ilm.</p>";
          echo "<p>Täna on "; 
          echo date("d.m.Y");
          echo ".</p>";
          echo "<p>Lehe laadimise hetkel oli kell: " .date("H:i:s") ."</p>";
          echo "Praegu on " .$partOfDay .".";
        ?>
        <p>PHP käivitatakse lehe laadimisel ja siis tehakse kogu töö ära. Hiljem, kui vaja midagi jälle "kalkuleerida", siis laetakse kogu leht uuesti.</p>
        <?php
          echo "<p>Lehe autori täisnimi on: " .$myName ." " .$myFamilyName .".</p>";
        ?>
      </div>
  </body>
</html>