<?php
include 'db/config.php';
// get the HTTP method, path and body of the request
CONST WHITELIST = ["name","photo","title","department_name"];

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
				if (!isset($_GET["url"])) {
					$error = 'Invalid param for GET';
					throw new Exception($error);
				}
				$url = $_GET["url"];
				$query = handle_get($url);
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
} catch (Exception $e) {
    $result = array(
        'status' => 'ERROR',
        'error' => "Connection failed: " . $e->getMessage()
    );
    print json_encode($result, JSON_PRETTY_PRINT);
    exit();
}

try {
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
		//fetch all results
		$fetched = $stmt->fetchAll();
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

function parse($url){
    // Splitting string by /
    $url_arr = preg_split("/\//", $url);

    // If there are odd number of parameters, the max even number of parameters
    // are stored in the associative array. The remaining one will be added
    // later and the value will be set to null.
    $odd = FALSE;
    if (sizeof($url_arr)%2==0){
        $max = sizeof($url_arr);
    }else{
        $max = sizeof($url_arr)-1;
        $odd = TRUE;
    }

    $parsed_url = array();
    for ($index = 0; $index<$max; $index+=2){
        $parsed_url[$url_arr[$index]] = $url_arr[$index+1];
    }

    if ($odd){
        $parsed_url[$url_arr[$max]] = NULL;
    }

    return $parsed_url;
}

function handle_post($data){
	$result = url_to_params(parse($data["url"]));

	$keys = "";
	$values = "";
	foreach($data['data'] as $key=>$value){
		$keys.=$key . ", ";
		$values.="'". $value . "', ";
	}
	$keys = rtrim($keys, ", ");
	$values = rtrim($values, ", ");

	check_columns(array_keys($data['data']), $result['table'], 'post');

	// try{
	// 	check_columns(array_keys($data['data']), $result['table'], 'post');
	// }catch(Exception $e){
	// 	die("Could not post.");
	// }
    //
	return "INSERT INTO ta_feedback.$result[table] ($keys) VALUES ($values);";

}

function handle_get($url){
	//parses 'url' field from url then parse it with the parse function
	$parsed_url = parse(urldecode($url));
	$result = url_to_params($parsed_url);
	$table = $result['table'];
	$conditions =  $result['condition'];
	check_columns(array_keys($data['data']), $result['table'], 'get');
	if ($conditions == ""){
		return "SELECT * FROM $table";
	}else{
		return "SELECT * FROM $table WHERE $conditions";
	}
}

/**
* @return returns an associative array that provies the table and condition to operate on
*/
function url_to_params($url){
	$result = array('table' => NULL, 'condition' => "");
	$i=0;
	foreach ($url as $key => $value) {
		if($value != NULL){
			switch($key){
				case 'users':
					$result['condition'] .= "utorid = '$value' AND ";
					break;
				case 'courses':
					$result['condition'] .= "course_code = '$value' AND ";
					break;
				case 'sections':
					$result['condition'] .= "section_id = $value AND ";
					break;
				case 'survey':
					$result['condition'] .= "id = $value AND ";
					break;
				case 'responses':
					$result['condition'] .= "response_id = $value AND ";
					break;
				default:
					$error = "invalid url key";
					throw new Exception($error);
			}
		}
		++$i;
		if($i == count($url)){
			$result['table'] = $key;
		}
	}
	$result['condition'] = substr($result['condition'], 0,-4);
	return $result;
}

/**
* @return returns an SQL statment that updates a row
*/
function handle_put($data)
{
	$url = parse($data["url"]);
	$result = url_to_params($url);
	check_columns(array_keys($data['data']), $result['table'], 'put');
	return update($result['table'], $data['data'], $result['condition']);
}

/**
* @return returns an SQL statment that deletes a row
*/
function handle_delete($data)
{
	$result = url_to_params(parse($data["url"]));
	check_columns(array_keys($data['data']), $result['table'], 'delete');
	return delete($result['table'], $result['condition']);
}

/**
* @return returns an SQL statment that deletes a row
*/
function delete($table,$condition)
{
	$query_stmt = "DELETE FROM $table WHERE $condition;";
	return $query_stmt;
}
function check_white_list($data){
	foreach ($data as $key => $value) {
		$column.= "$key = '$value',";
	}
}
/**
 * Checks if column exists within database.
 *
 * @param columns array containing column names
 * @param table table name
 * @param operation the operation performed (POST, GET, PUT, DELETE)
 *
 * @throws invalid_column error thrown if column doesn't exist
 */
function check_columns($columns, $table, $operation){
	$course_question_choice = array('question_id', 'user_id', 'locked', 'position');
	$courses = array('course_code', 'title', 'department_name');
	$department = array('name');
	$dept_question_choice = array('department_id', 'term', 'question_id', 'user_id', 'locked', 'position');
	$questions = array('answer_type', 'content');
	$response =array('survey_instance_id', 'question_id', 'answer', 'user_id');
	$sections = array('course_id', 'term', 'meeting_time', 'room', 'section_code');
	$survey_instances = array('user_association_id', 'override_token', 'time_window', 'start_time');
	$surveys =array('name', 'course_id', 'term', 'default_time_window', 'default_start_time');
	$ta_question_choice = array('section_id', 'term', 'question_id', 'user_id', 'locked', 'position');
	$user_association =array('user_id', 'course_id', 'section_code');
	$users =array('utorid', 'type', 'name1', 'photo1');

	$selected_table = NULL;
	switch($table){
		case 'course_question_choice':
			if ($operation!='delete'){
				$selected_table = $course_question_choice;
			}
			break;

		case 'courses':
			if ($operation!='put'){
				$selected_table = $courses;
			}
			break;

		case 'department':
			if ($operation!='delete'){
				$selected_table = $department;
			}
			break;

		case 'dept_question_choice':
			if ($operation!='delete'){
				$selected_table = $dept_question_choice;
			}
			break;

		case 'questions';
			if (($operation!='put')or($operation!='delete')){
				$selected_table = $questions;
			}
			break;

		case 'response':
			if (($operation!='put')or($operation!='delete')){
				$selected_table = $response;
			}
			break;

		case 'sections':
			if ($operation!='put'){
				$selected_table = $sections;
			}
			break;

		case 'survey_instances':
			if (($operation!='put')or($operation!='delete')){
				$selected_table = $survey_instances;
			}
			break;

		case 'surveys':
			if (($operation!='put')or($operation!='delete')){
				$selected_table = $surveys;
			}
			break;

		case '$ta_question_choice':
			if ($operation!='delete'){
				$selected_table = $ta_question_choice;
			}
			break;

		case 'user_association':
			$selected_table = $user_association;
			break;

		case 'users':
			$selected_table = $users;
			break;

		default:
			throw new Exception('Table: '. $table . ' does not exist');
			break;
	}
	if(!(isset($selected_table))){
		throw new Exception("Invalid operation");
	}

	if ($operation=='post'){
		if (sizeof($columns)!=sizeof($selected_table)){
			throw new Exception("Invalid number of columns");
		}
	}

	foreach($columns as $column){
		if (!in_array($column, $selected_table)){
			throw new Exception('Column: '. $column . ' does not exist');
		}
	}

	if ($operation=='put'){
		if ($selected_table == $users){
			if (sizeof($columns)>2){
				throw new Exception("Invalid request");
			}

			foreach($columns as $column){
				if (($column!='name1') && ($column!='photo1')){
					throw new Exception('Column: '. $column . ' modification not authorized');
				}
			}
		}
	}
}
function update($table, $data, $condition)
{
	$column = "";
	foreach ($data as $key => $value) {
		$column.= "$key = '$value',";
	}
	$column = rtrim($column, ", ");
	$query_stmt = "UPDATE $table SET $column WHERE $condition;";
	return $query_stmt;
}
?>
