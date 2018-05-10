<?php
$servername = "localhost";
$username = "myuser";
$password = "mypassword";
try {
    $conn = new PDO("mysql:host=$servername;dbname=mydb", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    $result = array('status' => 'ERROR', 'error' => "Connection failed: " . $e->getMessage());
    print(json_encode($result, JSON_PRETTY_PRINT));
}
?>
