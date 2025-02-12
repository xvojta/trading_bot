<?php
require_once(__DIR__  . '/../config/database.php');
$INDEX_USERNAME = 'username';
$INDEX_USER_ID = 'id';
$INDEX_LOGGED_IN = 'logged';
$logged_in;
$uid;
$username;

session_start();
fetch_session_data();

function fetch_session_data()
{
    global $INDEX_LOGGED_IN;
    global $INDEX_USER_ID;
    global $INDEX_USERNAME;
    global $logged_in;
    global $uid;
    global $username;
    if(isset($_SESSION[$INDEX_LOGGED_IN]))
    {
        $logged_in = $_SESSION[$INDEX_LOGGED_IN];
        if($logged_in)
        {
            $username = $_SESSION[$INDEX_USERNAME];
            $uid = $_SESSION[$INDEX_USER_ID];
        }
    }
    else
    {
        $logged_in = false;
    }

}

function login($uname, $passwd)
{
    global $pdo;
    global $INDEX_LOGGED_IN;
    global $INDEX_LOGGED_IN;
    global $INDEX_USER_ID;
    global $INDEX_USERNAME;

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bindParam(1, $uname);
    $stmt->execute(); // Execute the statement
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the results
    //return "stmt: " . json_encode($stmt) . "<br> data: " . json_encode($data);
    if(!isset($data) || $data == [])
        return ['succsess' => false, 'message' => "User with this username not found"];
    else {
        $data = $data[0];
        if($data['password'] == $passwd) {
            $_SESSION[$INDEX_LOGGED_IN] = true;
            $_SESSION[$INDEX_USER_ID] = $data['id'];
            $_SESSION[$INDEX_USERNAME] = $data['username'];
            fetch_session_data();
            return ['succsess' => true, 'message' => "User logged in successfully"];
        }
        else
            return ['succsess' => false, 'message' => "Wrong password for this user"];
    }
}

function register($uname, $passwd)
{
    global $pdo;
    global $INDEX_LOGGED_IN;
    global $INDEX_LOGGED_IN;
    global $INDEX_USER_ID;
    global $INDEX_USERNAME;

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES(?, ?)");
        $stmt->bindParam(1, $uname);
        $stmt->bindParam(2, $passwd);
        $stmt->execute();
    
        login($uname, $passwd);
        return ['succsess' => true, 'message' => "Registration successful"];
    } catch (\Throwable $th) {
        if(str_contains($th->getMessage(),'Duplicate entry'))
        {
            return ['succsess' => false, 'message' => "User already registered"];
        }
        return ['succsess' => false, 'message' => "Registration failed, SQL error"];
    }
}
?>