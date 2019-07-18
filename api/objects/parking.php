<?php
class Parking{
 
    // database connection and table name
    private $conn;
    private $table_name = "parking";
 
    // object properties
    public $id;
    public $name;
    public $description;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
        // select all query
        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created 
                FROM " . $this->table_name . " p
                ORDER BY p.created DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }    
}
?>