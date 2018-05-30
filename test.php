<?php
include 'db/config.php';
// get the HTTP method, path and body of the request
CONST WHITE_LIST = ["name","photo","title","department_name"];
try {
	$method = "";
    if (isset($_SERVER['REQUEST_METHOD'])) {
        $query = "";
        $method = $_SERVER['REQUEST_METHOD'];
		$data = json_decode(file_get_contents("php://input"), true);
        switch ($method) {
            case "POST":
                $url = parse($data["url"]);
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

	return "INSERT INTO ta_feedback.$result[table] ($keys) VALUES ($values);";

}

function handle_get(){

}


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

function handle_put($data)
{
	$url = parse($data["url"]);
	$result = url_to_params($url);
	var_dump($result);
	exit();
	return update($result['table'], $data['data'], $result['condition']);
}

function handle_delete($data)
{
	$result = get_params($data);
	return delete($result['table'], $result['condition']);
}

function delete($table,$condition){
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
 *
 * @throws invalid_column error thrown if column doesn't exist
 */
function check_columns($columns, $table){
	$course_question_choice = array('survey_id', 'question_id', 'user_id', 'locked', 'position');
	$courses = array('course_code', 'title', 'department_name');
	$department = array('name');
	$dept_question_choice = array('department_id', 'term', 'question_id', 'user_id', 'locked', 'position');
	$questions = array('question_id', 'answer_type', 'content');
	$response =array('response_id', 'survey_instance_id', 'question_id', 'answer', 'user_id');
	$sections = array('section_id', 'course_id', 'term', 'meeting_time', 'room', 'section_code');
	$survey_instances = array('id', 'user_association_id', 'override_token', 'time_window', 'start_time');
	$surveys =array('id', 'name', 'course_id', 'term', 'default_time_window', 'default_start_time');
	$ta_question_choice = array('survey_id', 'section_id', 'term', 'question_id', 'user_id', 'locked', 'position');
	$user_association =array('id', 'user_id', 'course_id', 'section_code');
	//TODO: fix database
	$users =array('utorid', 'type', 'name1', 'photo1');

	$selected_table;
	switch($table){
		case 'course_question_choice':
			$selected_table = $course_question_choice;
			break;

		case 'courses':
			$selected_table = $courses;
			break;

		case 'department':
			$selected_table = $department;
			break;

		case 'dept_question_choice':
			$selected_table = $dept_question_choice;
			break;

		case 'questions';
			$selected_table = $questions;
			break;

		case 'response':
			$selected_table = $response;
			break;

		case 'sections':
			$selected_table = $sections;
			break;

		case 'survey_instances':
			$selected_table = $survey_instances;
			break;

		case 'surveys':
			$selected_table = $surveys;
			break;

		case '$ta_question_choice':
			$selected_table = $ta_question_choice;
			break;

		case 'user_association':
			$selected_table = $user_association;
			break;

		case 'users':
			$selected_table = $users;
			break;
	}

	foreach($columns as $column){
		if (!in_array($column, $selected_table)){
			throw new Exception('Column: '. $column . ' does not exist');
		}
	}
	break;
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
