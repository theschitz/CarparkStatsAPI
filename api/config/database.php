<?php
include_once('config.php');

class Database {
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";
    public $conn;

    public function getConnection() {        
        $this->configure();
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;  
    }
    
    private function configure() {
        $cfg = parse_ini_file(CONFIG_INI_PATH, true);
        $this->conn = null;        
        $this->host = $cfg["database"]["host"];
        $this->db_name = $cfg["database"]["db_name"];
        $this->username = $cfg["database"]["username"];
        $this->password = $cfg["database"]["password"];
    }
}
?>