<?php
class Parking {
 
    private $conn;
    private $table_name = "parkering_jkpg";
    private $limit = 5000;
    private $orderby = "id";
    private $fromDT = null;
    private $toDT = null;
    private $parkingName = "%"; 
 
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
        $this->fromDT = new DateTime("2000-01-01");
        $this->toDT = new DateTime("2999-01-01");
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
        if (array_key_exists("fromDatetime", $filters)) {
            $this->fromDT = $filters["fromDatetime"];
        }
        if (array_key_exists("toDatetime", $filters)) {
            $this->toDT = $filters["toDatetime"];
        }
        if (array_key_exists("name", $filters)) {
            $this->parkingName = $filters["name"];
        }
    }

    function read(){
        $query = "SELECT * FROM {$this->table_name}
                  WHERE (datetime BETWEEN {$this->fromDT} AND {$this->toDT})
                  AND name LIKE {$this->parkingName}
                  ORDER BY {$this->orderby} LIMIT {$this->limit}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }    
}
?>