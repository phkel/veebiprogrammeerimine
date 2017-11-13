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
  $fetchAllUserinfo = fetchAllUserinfo();

  require("header.php");
?>

<p><a href="main.php">Pealeht</a></p>
<hr>
<h2>Kõik süsteemi kasutajad</h2>

<table border="1" style="border: 1px solid black; border-collapse: collapse">
<thead>
<tr>
  <th>Eesnimi</th>
  <th>Perekonnanimi</th>
  <th>Email</th>
  <th>Sünna</th>
  <th>Sugu</th>
</tr>
</thead>
<tbody>
<?php echo $fetchAllUserinfo ;?>
</tbody>
</table>

<p><a href="?logout=1">Logi välja</a></p>
<?php 
require("footer.php");
?>