<?php
include 'db/config.php';
// get the HTTP method, path and body of the request
try {
	$method = "";
    if (isset($_SERVER['REQUEST_METHOD'])) {
        $query = "";
        $method = $_SERVER['REQUEST_METHOD'];
		$data = json_decode(file_get_contents("php://input"), true);
        switch ($method) {
            case "POST":
				error_check($data, TRUE);
                $query = handle_post($data);
                break;
            case "GET":
				error_check($data, FALSE);
                $query = handle_get($data);
                break;
            case "PUT":
				error_check($data, TRUE);
                $query = handle_put($data);
                break;
            case "DELETE":
				error_check($data, FALSE);
                $query = handle_delete($data);
                break;
        }
    }else{
		$error = 'No Request Method Found';
		throw new Exception($error);
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

	if($method == "GET"){
		// set the resulting array to associative
		$fetched = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		if (count($query) >= 1) {
			$result['STATUS'] = 'OK';
			$result['DATA'] = $fetched;
		} else {
			$result = array('STATUS' => 'EMPTY');
		}
	}else{
		$result = array('STATUS' => 'SUCCESS');
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

	return update($table, $column, $condition);

}

function handle_delete($data)
{
	$table = $data["Entity"];
	$condition = $data["parameters"];

	return  delete($table, $condition);
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
function delete($table,$condition){
	$query_stmt = "";
	foreach($condition as $key => $value){
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
		$all_column .= "$col_key = '$col_value',";
	}
	foreach ($condition as $cond_key => $cond_value) {
		$all_condition .= "$cond_key = '$cond_value',";
	}
	$all_column = rtrim($all_column, ',');
	$all_condition = rtrim($all_condition, ',');
	$query_stmt .= "UPDATE $table SET $all_column WHERE $all_condition;";
	return $query_stmt;
}
