<?php
  //Muutujad
  $myName = "Kertu";
  $myFamilyName = "Kipper";
  $myAge = 0;
  $myBirthYear;
  $myLivedYearsList = "";

  $monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];

  //var_dump ($monthNamesEt);
  //echo $monthNamesEt[8];

  $hourNow = date("H");
  $partOfDay = "";

  if ($hourNow < 8) {
    $partOfDay = "varane hommik";
  }
  if ($hourNow >= 8 and $hourNow < 16) {
    $partOfDay = "koolipäev";
  }
  if ($hourNow > 16) {
    $partOfDay = "vaba aeg";
  }

  // Nüüd vaatame, kas ja mida kasutaja sisestas
  if (isset ($_POST["yearBirth"])) {
    $myBirthYear = $_POST["yearBirth"];
    $myAge = date("Y") - $myBirthYear;

    // tekitame loendi kõigist elatud aastatest
    $myLivedYearsList .= "<ul> \n";
    for ($i = $myBirthYear; $i <= date("Y"); $i++){
      $myLivedYearsList .= "<li>" .$i ."</li> \n";
    }
    $myLivedYearsList .= "</ul> \n";
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
          $monthIndex = date("n") - 1;  // n on kuu number ilma lisanullita ees 
          echo date("d. ") .$monthNamesEt[$monthIndex] .date(" Y");
          echo ".</p>";
          echo "<p>Lehe laadimise hetkel oli kell: " .date("H:i:s") ."</p>";
          echo "Praegu on " .$partOfDay .".";
        ?>
        <p>PHP käivitatakse lehe laadimisel ja siis tehakse kogu töö ära. Hiljem, kui vaja midagi jälle "kalkuleerida", siis laetakse kogu leht uuesti.</p>
        <?php
          echo "<p>Lehe autori täisnimi on: " .$myName ." " .$myFamilyName .".</p>";
        ?>
        <h2>Vanus</h2>
        <p>Järgnevalt palume sisestada oma sünniaasta</p>
        <form method="POST"> 
          <label>Teie sünniaasta: </label>
          <input id="yearBirth" name="yearBirth" type="number" min="1900" max="2017" value="<?php echo $myBirthYear; ?>">
          <input id="submitYearBirth" name="submitYearBirth" type="submit" value="Kinnita">
        </form>
        <p>Teie vanus on <?php echo $myAge; ?> aastat.</p>
        <?php
          if ($myLivedYearsList != "") {
            echo "<h3>Oled elanud järgnevatel aastatel</h3> \n";
            echo $myLivedYearsList;
          }
        ?>
        <h2>Paar linki</h2>
        <p>Õpin <a href="http://www.tlu.ee" target="_blank">TLÜs.</a></p>
        <p>Minu esimene php leht on <a href="../esimene.php" target="_blank">siin</a>.</p>
        <p>Minu sõber Kevin teeb veebi <a href="../../../~kodakevi/veebiprogrammeerimine">siin</a>.</p>
        <p>Pilte ülikoolist näeb <a href="foto.php">siin</a>.</p>
      </div>
  </body>
</html>
