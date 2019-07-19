<?php
class Parking{
 
    // database connection and table name
    private $conn;
    private $table_name = "parkering_jkpg";
    private $limit = 5000;
    private $orderby = "id";
 
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
    }

    public function setFilters($filters) {
        if (array_key_exists("limit", $filters)) {
            if ($filters["limit"] < $this->limit) {
                $this->limit = $filters["limit"];
            }
        }
        if (array_key_exists("orderby", $filters)) {
            if (array_key_exists($filters["orderby"], array("name", "occupancy"))) {
                $this->orderby = $filters["orderby"];
            }
        }
        #TODO: add filter for fromDatetime, toDatetime and name.
        #TODO: Filter for ID?
    }

    function read(){
        $query = "SELECT * FROM {$this->table_name} ORDER BY {$this->orderby} LIMIT {$this->limit}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }    
}
?>