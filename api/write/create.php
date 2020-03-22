<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); # TODO authorization??
header("Cache-Control: no-cache");
header("Pragma: no-cache");

include_once 'config/database.php';
include_once 'objects/parking.php';

#TODO: Test
#TODO: Add some kind of Authorization

$database = new Database();
$db = $database->getConnection(); 
$parking = new Parking($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
if (data_is_incomplete($data)) {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create entry. Data is incomplete."));
    die();
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
        echo json_encode(array("message" => $parking->conn->errorInfo()));
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
?>