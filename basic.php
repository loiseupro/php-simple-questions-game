<?php

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// For debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "classes/qaClass.php";
$the_qa = new qa();

/* Restart */
if (isset($_GET["option"]) && $_GET["option"] == "restart") {
    $the_qa->restart();
    header("Location: ?");
}

/* Check answer */
if (isset($_GET["option"]) && $_GET["option"] == "check") {
    if (isset($_GET["id_question"]) && isset($_GET["id_answer"])) {
        $response = $the_qa->checkAnswer($_GET["id_question"], $_GET["id_answer"]);
        if ($response) {
            $the_qa->incrementSuccess();
            $msg = "<p style='color:green;'>Response is OK!<br/><a href='?'>Press to continue...</a></p>";
        } else {
            $the_qa->incrementFail();
            $msg = "<p style='color:red;'>Response is NOT OK!<br/><a href='?'>Press to continue...</a></p>";
        }
    }
} else {
    /* Try to load new question */
    $question = $the_qa->getQuestion();
    if (!isset($question["question"])) {
        $msg = "<p style='color:purple;'>It's all sold!</p>";
    }
}

?>
<html>

<head>
    <title>The QA</title>
</head>

<body>
    <span style='color:green;'><b>Success:</b> <?php echo $the_qa->getSuccess(); ?></span>
    <span style='color:red; margin-left:10px;'><b>Fail:</b> <?php echo $the_qa->getFail(); ?></span>
    <a style="margin-left: 30px;" href="?option=restart">Restart</a>
    <hr/>
    <?php
    if (isset($msg)) {
        echo $msg;
    } else {
    ?>
        <p><label><?php echo $question["question"]; ?></label></p>
        <ul>
            <?php
            foreach ($question["answer"] as $answer) {
            ?>
                <li>
                    <a href="?option=check&id_question=<?php echo $question["id_question"]; ?>&id_answer=<?php echo $answer["id"]; ?>">
                        <?php echo $answer["value"]; ?>
                    </a>
                </li>
            <?php
            }
            ?>
        </ul>
    <?php
    }
    ?>
    
</body>

</html>