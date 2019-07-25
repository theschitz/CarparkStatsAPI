<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST"); #TODO: Post or get??
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); # TODO authorization??
header("Cache-Control: no-cache");
header("Pragma: no-cache");

include_once 'config/database.php';
include_once 'objects/parking.php';

$database = new Database();
$db = $database->getConnection(); 
$parking = new Parking($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
if (data_is_incomplete($data)) {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create entry. Data is incomplete."));   
}

foreach ($data as $p) {
    $parking->name = $p->name;
    $parking->maxoccupancy = $p->maxOccupancy;
    $parking->occupancy = $p->occupancy;
    $parking->marginal = $p->marginal;
    $parking->hysteres = $p->hysteres;
    $parking->active = $p->active;
    $parking->datetime = date('Y-m-d H:i:s'); #TODO make this right.

    // create entry
    if($parking->create()){
        //201 -  created
        http_response_code(201);
        echo json_encode(array("message" => "Entry created."));
    }
    else{
        //503 service unavailable
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create entry."));
    }
}

function data_is_incomplete($data) {
    foreach ($data as $p) {
        if (
            !empty($p->name) && 
            !empty($p->maxOccupancy) && 
            !empty($p->occupancy) && 
            !empty($p->marginal) &&
            !empty($p->hysteres) && 
            !empty($p->active)
           ){
                return true;
        }
    }
    return false;
}
//gammal kod.---->>>>>>>

$datetime = date("Y-m-d H:i:s");
$date = date("Y-m-d");
$time = date("H:i:s");

/* Validate Token */
if(!isset($_GET['token'])) {
	echo('Error: Not allowed.');
	die();
}
# TODO: Bring back token?
/* Validate Token */
// Ta emot data
if (!isset($_GET['json'])) {
        echo('Error: Bad data.');
        die();
}
$in_json = $_GET['json'];
$in_data = json_decode($in_json, true);


// SQL Connect
$mysqli = new mysqli($host, $username, $password, $dbname);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_errno());
	die();
}

/* change character set to utf8 */
if (!$mysqli->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $mysqli->error);
    die();
}
//Loop & Insert Array
foreach ($in_data as &$parkings) {
	$query = "INSERT INTO parkering_jkpg (datetime, name, occupancy, maxoccupancy, marginal, hysteres, active) 
			  VALUES ('{$datetime}','{$parkings['name']}','{$parkings['occupancy']}','{$parkings['maxOccupancy']}','{$parkings['marginal']}','{$parkings['hysteres']}','{$parkings['active']}')";
	if (!$mysqli->query($query)) {		
		http_response_code(500);
		echo json_encode(array("error" => $mysqli->error));
		die();
	}
}
$mysqli->close();
http_response_code(200);
echo 'OK';
die();
?>