<?php
include 'db/config.php';
// get the HTTP method, path and body of the request
if (ISSET($_SERVER['REQUEST_METHOD'])){
	$query = "";
	$method = $_SERVER['REQUEST_METHOD'];
	switch ($method) {
		case "POST":
			$data = file_get_contents('php://input');
			$query = handle_post($data);
			break;
	  	case "GET":
			$data = $_GET['DATA'];
			$query = handle_get($data);
			break;
		case "PUT":
			$data = file_get_contents('php://input');
			$query = handle_put($data);
			break;
		case "DELETE":
			$data = file_get_contents('php://input');
			$query = handle_delete($data);
			break;
	}
}



function handle_post ($data){
	// $course = mysql_real_escape_string($conn, $_POST['course']);
	// $name = mysql_real_escape_string($conn, $_POST['name']);
	// $tutorial = mysql_real_escape_string($conn, $_POST['tutorial']);
	//echo $_POST['course'];
	$obj = json_decode($data,true);
	// echo $obj['course'];
	// echo $obj['name'];
	// echo $obj['tutorial'];
	echo "INSERT INTO mydb.courses (Course, TA, Section) VALUES ($obj[course], $obj[name], $obj[tutorial]);";
	return "INSERT INTO mydb.courses (Course, TA, Section) VALUES ($obj[course],$obj[name],$obj[tutorial]);";
	//return "insert into mydb.courses values('$obj['course']','$obj['name']','$obj['tutorial']');";

	exit();
}
function handle_get ($data){

}
function handle_put (){

}
function handle_delete (){

}
// connect to the mysql database
$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);

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
