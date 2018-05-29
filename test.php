<?php
include 'db/config.php';
// get the HTTP method, path and body of the request
try {
	if (isset($_SERVER['REQUEST_METHOD'])) {
	    $query = "";
	    $method = $_SERVER['REQUEST_METHOD'];
	    switch ($method) {
	        case "POST":
	            $data = json_decode(file_get_contents("php://input"), true);
				error_check($data, TRUE);
	            $query = handle_post($data);
	            break;
	        case "GET":
	            $data = $_GET['DATA'];
				error_check($data, FALSE);
	            $query = handle_get($data);
	            break;
	        case "PUT":
	            $data = json_decode(file_get_contents("php://input"), true);
				error_check($data, TRUE);
	            $query = handle_put($data);
	            break;
	        case "DELETE":
	            $data = json_decode(file_get_contents("php://input"), true);
				error_check($data, FALSE);
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

function error_check($data, $need_data){
	if (!isset($data["url"])){
		$error = "No URL Selected";
		throw new Exception($error);
	}
	if ($need_data){
		if (!isset($data["data"])){
			$error = "No Data";
			throw new Exception($error);
		}
	}
}

function handle_post($data){
	$keys = "";
	$values = "";
	foreach($data['data'] as $key=>$value){
		$keys.=$key . ", ";
		$values.=$value . ", ";
	}
	$keys = rtrim($keys, ", ");
	$values = rtrim($values, ", ");
	
	return "INSERT INTO mydb.courses ($keys) VALUES ($values);";

	exit();
}

function handle_get(){

}
function handle_put($data)
{
	$table = $data["Entity"];
	$column = $data["Entries"];
	$row = $data["ID"];
	$query_stmt = "";

	return $query_stmt;
}

function handle_delete()
{
	$table = $data["Entity"];
	$condition = $data["id"];
	$query_stmt = "";

	return delete($table,$condition);
}
function delete($table,$condition){
	$query_stmt = "";
	foreach($condition as $key => $value){
		$query_stmt .= "DELETE FROM $table WHERE $key = $value;";
	}
	return $query_stmt;
}
function update($table,$column,$condition){
	$query_stmt = "";
	foreach($column as $key => $value){
		foreach($column as $key => $value){
			$query_stmt .= "UPDATE $table SET $key = $value WHERE $row;";
		}
		$query_stmt .= "UPDATE $table SET $key = $value WHERE $row;";
	}
	return $query_stmt;
}
function arr_get($array, $key, $default = null)
{
	return isset($array[$key]) ? $array[$key] : $default;
}
