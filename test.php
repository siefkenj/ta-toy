<?php
include 'db/config.php';
// get the HTTP method, path and body of the request
try {
	if (isset($_SERVER['REQUEST_METHOD'])) {
	    $query = "";
	    $method = $_SERVER['REQUEST_METHOD'];
	    switch ($method) {
	        case "POST":
	            $data = $_POST['DATA'];
	            $query = handle_post($data);
	            break;
	        case "GET":
	            $data = $_GET['DATA'];
	            $query = handle_get($data);
	            break;
	        case "PUT":
	            $data = json_decode(file_get_contents("php://input"), true);
	            $query = handle_put($data);
	            break;
	        case "DELETE":
	            $data = json_decode(file_get_contents("php://input"), true);
	            $query = handle_delete($data);
	            break;
	    }
	}

	function handle_post()
	{

	}
	function handle_get()
	{

	}
	function handle_put($data)
	{
		if (!isset($data["Entity"])){
			$error = 'No Entity Selected';
	        throw new Exception($error);
		}
		if (!isset($data["Entries"])){
			$error = 'No Entries Selected';
			throw new Exception($error);
		}
		if (!isset($data["ID"])){
			$error = 'No ID Selected';
			throw new Exception($error);
		}
		$table = $data["Entity"];
		$column = $data["Entries"];
		$row = $data["ID"];
		$query_stmt = "";

	    return $query_stmt;
	}

	function update($table_name,$condition = )
	function handle_delete()
	{
		if (!isset($data["Entity"])){
			$error = 'No Entity Selected';
			throw new Exception($error);
		}

		if (!isset($data["ID"])){
			$error = 'No ID Selected';
			throw new Exception($error);
		}
		$table = $data["Entity"];
		$condition = $data["id"];
		$query_stmt = "";

		return delete($table,$condition);
	}
	function delete($table,$condition){
		$query_stmt = "";
		foreach($condition as $key => $value){
			$query_stmt .= "DELETE FROM $table WHERE $key = $value;"
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
