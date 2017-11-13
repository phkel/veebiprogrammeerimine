<?php
    require("../../../config.php");
    $database = "if17_kippkert";

    //ühe konkreetse mõtte lugemine
    function getSingleIdeaData($edit_id) {
        $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $mysqli->prepare("SELECT idea, ideaColor FROM vpuserideas WHERE id = ?");
        $stmt->bind_param("i", $edit_id);
        $stmt->bind_result($ideaText, $ideaColor);
        $stmt->execute();
        //loon objekti 
        $ideaObject = new Stdclass();
        if($stmt->fetch()) {
            $ideaObject->text = $ideaText;
            $ideaObject->color = $ideaColor;
        }
        $stmt->close();
        $mysqli->close();

        return $ideaObject;
    }

    //
    function updateIdea($id, $idea, $color) {
        $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $mysqli->prepare("UPDATE vpuserideas SET idea=?, ideacolor=? WHERE id=? AND deleted IS NULL");
        echo $mysqli->error;
        $stmt->bind_param("ssi", $idea, $color, $id);
        if($stmt->execute()) {
            echo "õnnestus";
        } else {
            echo $stmt->error;
        }
        $stmt->close();
        $mysqli->close();
    }
?>