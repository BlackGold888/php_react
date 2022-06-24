<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private $host = "localhost";
    private $db_name = "mydb";
    private $username = "root";
    private $password = "123123123";
    public $conn;
    
    /**
     * @return Database|null
     */
    public static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * @return PDO|null
     */
    public function dbConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}