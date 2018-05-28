<?php
include 'db/config.php';
// get the HTTP method, path and body of the request
if ISSET($_SERVER['REQUEST_METHOD']){
	$query = "";
	$method = $_SERVER['REQUEST_METHOD'];
	if ($method) {
		case "POST":
			$data = $_POST['DATA']
			$query = handle_post($data)
			break;
	  	case "GET":
			$data = $_GET['DATA']
			$query = handle_get($data)
			break;
		case "PUT":
			$data = file_get_contents('php://input')
			$query = handle_put($data)
			break;
		case "DELETE"
			$data = file_get_contents('php://input')
			$query = handle_delete($data)
			break;
	}
}



function handle_post (){

}
function handle_get (){

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
