<?php
  require("functions.php");
  require("ideaeditfunctions.php");

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

  //kas uuendatakse
  if (isset($_POST["update"])) {
    // echo $_POST["id"];
    updateIdea($_POST["id"], test_input($_POST["idea"]), $_POST["ideaColor"]);
    header("Location: ideaedit.php");
    exit();
  }

  //Loen muudetava mõtte
  if (isset($_GET["id"])) {
    $idea = getSingleIdeaData($_GET["id"]);
  } else {
    header("Location: usersideas.php");
  }

  require("header.php");
?>

<p><a href="usersideas.php">Tagasi ideede lehele</a></p>
<p><a href="?logout=1">Logi välja</a></p>
<hr>
<h2>Hea mõtte toimetamine</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
  <label>Hea mõte: </label>
  <textarea name="idea"><?php echo $idea->text; ?></textarea>
  <br>
  <label>mõttega seostuv värv: </label>
  <input name="ideaColor" type="color" value="<?php echo $idea->color; ?>">
  <br>
  <input name="update" type="submit" value="Salvesta muudatused">
  <span><?php echo $notice; ?></span>
</form>
<?php 
require("footer.php");
?>