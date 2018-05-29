<?php
include 'db/config.php';
// get the HTTP method, path and body of the request
try {
    if (isset($_SERVER['REQUEST_METHOD'])) {
        $query = "";
        $method = $_SERVER['REQUEST_METHOD'];
		$data = json_decode(file_get_contents("php://input"), true);
        switch ($method) {
            case "POST":
                $query = handle_post($data);
                break;
            case "GET":
                $query = handle_get($data);
                break;
            case "PUT":
                $query = handle_put($data);
                break;
            case "DELETE":
                $query = handle_delete($data);
                break;
        }
    }
    // connect to the mysql database
    $conn = new PDO(
        "mysql:host=$servername;dbname=$databasename",
        $username,
        $password
    );

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // set the resulting array to associative
    $fetched = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    if (count($query) >= 1) {
        $result['STATUS'] = 'OK';
        $result['DATA'] = $fetched;
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

function handle_post()
{

}
function handle_get()
{

}
function handle_put($data)
{
	if (!isset($data["Entity"])) {
		$error = 'No Entity Selected';
		throw new Exception($error);
	}
	if (!isset($data["data"])) {
		$error = 'No data Selected';
		throw new Exception($error);
	}
	if (!isset($data["parameters"])) {
		$error = 'No parameters Selected';
		throw new Exception($error);
	}
	$table = $data["Entity"];
	$column = $data["data"];
	$condition = $data["parameters"];
	$query_stmt = "";

	echo update($table, $column, $condition);
	exit();
}

function handle_delete($data)
{
	if (!isset($data["Entity"])) {
		$error = 'No Entity Selected';
		throw new Exception($error);
	}

	if (!isset($data["parameters"])) {
		$error = 'No parameters Selected';
		throw new Exception($error);
	}
	$table = $data["Entity"];
	$condition = $data["parameters"];

	echo delete($table, $condition);
	exit();
}

function delete($table, $condition)
{
	$query_stmt = "";
	foreach ($condition as $key => $value) {
		$query_stmt .= "DELETE FROM $table WHERE $key = $value;";
	}
	return $query_stmt;
}

function update($table, $column, $condition)
{
	$query_stmt = "";
	$all_column = "";
	$all_condition = "";
	foreach ($column as $col_key => $col_value) {
		$all_column .= "$col_key = $col_value,";
	}
	foreach ($condition as $cond_key => $cond_value) {
		$all_condition .= "$cond_key = $cond_value,";
	}
	$all_column = rtrim($all_column, ',');
	$all_condition = rtrim($all_condition, ',');
	$query_stmt .= "UPDATE $table SET $all_column WHERE $all_condition;";
	return $query_stmt;
}
function arr_get($array, $key, $default = null)
{
	return isset($array[$key]) ? $array[$key] : $default;
}
