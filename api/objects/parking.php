<?php
class Parking{
 
    // database connection and table name
    private $conn;
    private $table_name = "parkering_jkpg";
    private $limit = 5000;
 
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

    function read(){
        $query = "SELECT * FROM {$this->table_name} LIMIT {$this->limit}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }    
}
?>