<?php
// Load database configurations
include '../db/config.php';
function arr_get($array, $key, $default = null)
{
    return isset($array[$key]) ? $array[$key] : $default;
}
$course = arr_get($_GET, 'course', '');
$ta = arr_get($_GET, 'ta', '');
try {
    $conn = new PDO("mysql:host=$servername;dbname=mydb", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = null;
    if ($course == '') {
        $stmt = $conn->prepare("SELECT DISTINCT Course FROM courses;");
        $result['TYPE'] = "courses";
    } elseif ($ta == '') {
        $stmt = $conn->prepare(
            "SELECT DISTINCT TA FROM courses WHERE Course ='" . $course . "';"
        );
        $result['TYPE'] = "ta";
    } elseif ($ta != '') {
         $stmt = $conn->prepare(
            "SELECT DISTINCT Sections FROM courses WHERE Course ='" .
                $course .
                "'AND TA = '" .
                $ta .
                "';"
        );
        $result['TYPE'] = "section";
    } else {
        $error = 'Invalid params';
        throw new Exception($error);
    }
    $stmt->execute();
    // set the resulting array to associative
    $query = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    if (count($query) >= 1) {
        $result['STATUS'] = 'OK';
        $result['DATA'] = $query;
    } else {
        $result = array('STATUS' => 'EMPTY');
    }
    echo json_encode($result);
} catch (PDOException $e) {
    $result = array(
        'status' => 'ERROR',
        'error' => "Connection failed: " . $e->getMessage()
    );
    print json_encode($result, JSON_PRETTY_PRINT);
    exit();
}
?>
