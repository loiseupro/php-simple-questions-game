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
            $result = true;
        } else {
            $the_qa->incrementFail();
            $result = false;
        }
        header("Location: ?result=" . $result);
    }
} else {
    /* Try to load new question */
    $question = $the_qa->getQuestion();
    if (!isset($question["question"])) {
        $msg = "This game has ended! To restart, press the top-right button called 'Restart'";
    }
}

/* For show result */
if (isset($_GET["result"])) {
    if ($_GET["result"]) {
        $result = true;
    } else {
        $result = false;
    }
}

?>
<html>

<head>
    <title>The QA</title>
    <link href='https://fonts.googleapis.com/css?family=Work Sans' rel='stylesheet'>
    <link href="http://fonts.cdnfonts.com/css/games" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="assets/custom.css">
</head>

<body>
    <div id="main" class="container">
        <div class="row mt-2 mb-5 pt-5 pb-2">
            <div class="col-md-3">
                <span class="badge bg-success">Success: <?php echo $the_qa->getSuccess(); ?></span>
                <span class="badge bg-danger">Fail: <?php echo $the_qa->getFail(); ?></span>
            </div>
            <div class="col-md-6 text-center the-header">
                <h1>THE PHP QUESTIONS GAME</h1>
                <p class="lead">This is a simple question and answer game programmed in PHP.</p>
            </div>
            <div class="col-md-3 justify-content-end">
                <a class="btn btn-outline-light" href="?option=restart">Restart</a>
            </div>
        </div>

        <div class="row">
            <?php
            if (isset($msg) || isset($result)) {
            ?>
                <div class="col-md-6 offset-md-3 pt-3 pb-3 panel-game">

                    <?php
                    if (isset($result) && $result) {
                    ?>
                        <div class="alert alert-success">
                            <h4 class="alert-heading">Well done!</h4>
                            Response is OK!
                        </div>
                    <?php
                    }
    
                    if (isset($result) && !$result) {
                    ?>
                        <div class="alert alert-danger">
                            <h4 class="alert-heading">FAIL! FAIL! FAIL!</h4>
                            Response is NOT OK!
                        </div>
                    <?php
                    }
                    ?>

                    <?php
                    if (isset($msg)) {
                    ?>
                        <div class="alert alert-ingo"> <?php echo $msg; ?></div>
                    <?php
                    }
                    if (isset($result)) {
                    ?>
                        <a class="btn btn-secondary " href='?'>Press to continue...</a>
                    <?php
                    }
                    ?>
                </div>
            <?php
            }

            if (!isset($msg) && !isset($result)) {
            ?>
                <div class="col-md-6 offset-md-3 pt-3 pb-3 panel-game">
                    <p class="text-center"><i>Question <?php echo $the_qa->getSuccess() + $the_qa->getFail() + 1; ?></i></p>
                    <h2 class="text-center"><?php echo $question["question"]; ?></h2>
                    <ul class="list-answer">
                        <?php
                        foreach ($question["answer"] as $answer) {
                        ?>
                            <li class="answer">
                                <a href="?option=check&id_question=<?php echo $question["id_question"]; ?>&id_answer=<?php echo $answer["id"]; ?>">
                                    <?php echo $answer["value"]; ?>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</body>

</html>