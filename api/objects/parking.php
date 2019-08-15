<?php
class Parking {
 
    private $conn;
    private $table_name = "parkering_jkpg";
    private $limit = 5000;
    private $orderby = "id";
    private $fromDT = null;
    private $toDT = null;
    private $parkingName = "%";
    private $validOrderByColumns = ["name" => 0, "occupancy" => 0];
    private $availableParkingAreas = ["Spira", "P-hus Biblioteket", "Östra Torget",
                                        "P-hus Atollen", "P-garage Järnbäraren", "P-hus Per Brahe",
                                        "P-hus Smedjan", "Västra Torget", "P-hus Sesam"	];
 
    // object properties
    public $id;
    public $datetime;
    public $name;
    public $occupancy;
    public $maxoccupancy;
    public $marginal;
    public $hysteres;
    public $active;
 
    public function __construct($db){
        $this->conn = $db;
        $this->fromDT = $this->formatDateTime("2000-01-01");
        $this->toDT = $this->formatDateTime("2999-01-01");
    }

    private function formatDateTime(string $dt) {
        return (new DateTime("{$dt}"))->format("Y-m-d H:i:s");
    }

    public function setFilters(array $filters) {
        if (array_key_exists("limit", $filters)) {
            if ($filters["limit"] < $this->limit) {
                if ((int)$filters["limit"]) {
                    $this->limit = (int)$filters["limit"];    
                } else {
                    $this->invalidParam("limit", $filters["limit"]);
                }
            }
        }
        if (array_key_exists("orderby", $filters)) {
            if (array_key_exists($filters["orderby"], $this->validOrderByColumns)) {
                $this->orderby = $filters["orderby"];
            }
        }
        if (array_key_exists("fromDatetime", $filters)) {
            try {
                $this->fromDT = $this->formatDateTime($filters["fromDatetime"]);
            } catch (Exception $ex) {
                $this->invalidParam("fromDatetime", $filters["fromDatetime"]);
            }
            

        }
        if (array_key_exists("toDatetime", $filters)) {
            try {
                $this->toDT = $this->formatDateTime($filters["toDatetime"]);
            } catch (Exception $ex) {
                $this->invalidParam("toDatetime", $filters["fromDatetime"]);
            }
        }
        if (array_key_exists("name", $filters)) {
            if (in_array($filters["name"], $this->availableParkingAreas)) {
                $this->parkingName = $filters["name"];
            } else {
                $this->invalidParam("name", $filters["name"]);
            }
        }
    }

    function read() {
        $query = "SELECT * FROM {$this->table_name}
                  WHERE (datetime BETWEEN '{$this->fromDT}' AND '{$this->toDT}')
                  AND name LIKE '{$this->parkingName}'
                  ORDER BY {$this->orderby} LIMIT {$this->limit}";
        #print_r($query);
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create() {
        # TODO: Add permissions/ Restrict access.
        $query = "INSERT INTO {$this->table_name} 
                  SET name=:name, 
                      datetime=:datetime, 
                      occupancy=:occupancy, 
                      maxoccupancy=:maxoccupancy, 
                      marginal=:marginal,
                      hysteres=:hysteres,
                      active=:active";
    
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->datetime=htmlspecialchars(strip_tags($this->datetime));
        $this->occupancy=htmlspecialchars(strip_tags($this->occupancy));
        $this->maxoccupancy=htmlspecialchars(strip_tags($this->maxoccupancy));
        $this->marginal=htmlspecialchars(strip_tags($this->marginal));
        $this->hysteres=htmlspecialchars(strip_tags($this->hysteres));
        $this->active=htmlspecialchars(strip_tags($this->active));
    
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":datetime", $this->datetime);
        $stmt->bindParam(":occupancy", $this->occupancy);
        $stmt->bindParam(":maxoccupancy", $this->maxoccupancy);
        $stmt->bindParam(":marginal", $this->marginal);
        $stmt->bindParam(":hysteres", $this->hysteres);
        $stmt->bindParam(":active", $this->active);
    
        if($stmt->execute()){
            return true;
        }
        return false;
        
    }

    private function invalidParam($param, $value) {
        http_response_code(400);
        echo json_encode(
            array("message" => "Parameter {$param} has an invalid value: {$value}.")
        );
        die();
    }
}
?>