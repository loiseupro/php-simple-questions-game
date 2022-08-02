<?php

/**
 * Basic question and answer
 */
class qa {


    /**
     * @var string database server name
     */
    private $servername = "localhost";

    /**
     * @var string database username
     */
    private $username = "root";

    /**
     * @var string database password
     */
    private $password = "";

    /**
     * @var string database name
     */
    private $database = "the_qa";

    /**
     * @var object database connection instance
     */
    private $connection;

    /**
     * @var string the cookie name to showed questions
     */
    private $cookieAlready = "the_qa_answered";

    /**
     * @var string the cookie name to success answers
     */
    private $cookieSuccess = "the_qa_success";

    /**
     * @var string the cookie name to fail answers
     */
    private $cookieFail = "the_qa_fail";

    /**
     * @var string answers table name
     */
    private $a_table = "answer";

    /**
     * @var string questions table name
     */
    private $q_table = "question";

    /**
     * @var array questions already answered
     */
    private $answered = [];

    /**
     * @var int the success answers counter
     */
    private $success = 0;

    /**
     * @var int the fail answers counter
     */
    private $fail = 0;

    /**
     * Init database connection and get already questions from cookie
     */
    public function __construct() {

        // Create database connection
        $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->database);

        // Check database connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        // Database Charset
        $this->connection->set_charset("utf8");

        // Cookies
        if (isset($_COOKIE[$this->cookieAlready])) {
            $this->answered = json_decode($_COOKIE[$this->cookieAlready], true);
        } else {
            setcookie($this->cookieAlready, json_encode($this->answered), time() + 3600);
        }

        if (isset($_COOKIE[$this->cookieSuccess])) {
            $this->success = $_COOKIE[$this->cookieSuccess];
        } else {
            setcookie($this->cookieSuccess, $this->success, time() + 3600);
        }

        if (isset($_COOKIE[$this->cookieFail])) {
            $this->fail = $_COOKIE[$this->cookieFail];
        } else {
            setcookie($this->cookieFail, $this->fail, time() + 3600);
        }
        
    }

    /**
     * Get new question 
     * @return array
     */
    public function getQuestion() {
        $data = array();
        $sql = "SELECT * FROM " . $this->q_table . " WHERE 1=1 ";
        if (count($this->answered) > 0) {
            $sql .= "AND id NOT IN (" . implode(',', $this->answered) . ") ";
        }
        $sql .= "ORDER BY views ASC LIMIT 1  ";

        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = array(
                    "id_question" => $row["id"],
                    "question" => $row["value"],
                    "answer" => $this->getQuestionAnswer($row["id"])
                );
                $this->addView($row["id"]);
            }
        }

        return $data;
    }

    /**
     * Get answers for a question
     * @param int $id_question
     * 
     * @return array
     */
    public function getQuestionAnswer(int $id_question) {
        $data = array();
        $sql = "SELECT id, value FROM " . $this->a_table . "  WHERE id_question= " . $id_question;
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = (array)$row;
            }
        }

        return $data;
    }

    /**
     * Check answer
     * @param int $id_question
     * @param int $id_answer
     * 
     * @return bool
     */
    public function checkAnswer(int $id_question, int $id_answer) {
        $sql = "SELECT id FROM " . $this->a_table . "  
        WHERE id_question= " . $id_question . " 
        AND id = " . $id_answer . " 
        AND correct = 1";
        $result = $this->connection->query($sql);

        $this->saveAnswered($id_question);

        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }

    /**
     * Increment views of question
     * @param int $id_question
     * 
     * @return bool
     */
    private function addView(int $id_question) {
        $sql = "UPDATE  " . $this->q_table . " SET views = views + 1 WHERE id =" . $id_question;

        if ($this->connection->query($sql) === TRUE) {
            return true;
        }

        echo "Error updating record: " . $this->connection->error;
        exit();
    }

    /**
     * Update questions already answered
     * @param int $id_question
     * 
     * @return bool
     */
    public function saveAnswered(int $id_question) {
        $this->answered = array_merge($this->answered, [$id_question]);

        if (isset($_COOKIE[$this->cookieAlready])) {
            setcookie($this->cookieAlready, json_encode($this->answered), time() + 3600);
        }

        return true;
    }

    /**
     * Update success answers counter
     * 
     * @return bool
     */
    public function incrementSuccess() {
        $this->success = $this->success + 1;

        if (isset($_COOKIE[$this->cookieSuccess])) {
            setcookie($this->cookieSuccess, $this->success, time() + 3600);
        }

        return true;
    }

    /**
     * Get current success count
     * 
     * @return int
     */
    public function getSuccess() {
        return (int)$this->success;
    }

    /**
     * Update fail answers counter
     * 
     * @return bool
     */
    public function incrementFail() {
        $this->fail = $this->fail + 1;

        if (isset($_COOKIE[$this->cookieFail])) {
            setcookie($this->cookieFail, $this->fail, time() + 3600);
        }

        return true;
    }

    /**
     * Get current fail count
     * 
     * @return int
     */
    public function getFail() {
        return (int)$this->fail;
    }

    /**
     * Restart. Empty already questions, fail ounter and success counter
     * @return bool
     */
    public function restart() {
        $this->answered = array();
        setcookie($this->cookieAlready, json_encode($this->answered), time() + 3600);

        $this->success = 0;
        setcookie($this->cookieSuccess, json_encode($this->success), time() + 3600);

        $this->fail = 0;
        setcookie($this->cookieFail, json_encode($this->fail), time() + 3600);
        return true;
    }
}
