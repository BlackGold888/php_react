<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class User
{
    private $conn;
    private $table_name = "users";
    
    public $id;
    public $username;
    public $password;
    public $email;
    public $isLoggedIn;
    
    public function __construct()
    {
        
        $this->conn = Database::getInstance()->dbConnection();
    }
    
    
    /**
     * @return bool
     */
    public function create(): bool
    {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password, email=:email";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $exception) {
            echo json_encode(array("error" => "Error " . $this->email . " already exists."));
            return false;
        }
    }
    
    /**
     * @return bool
     */
    public function login(): bool
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username=:username AND password=:password";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        try {
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row["id"];
                $this->username = $row["username"];
                $this->password = $row["password"];
                $this->email = $row["email"];
                $this->isLoggedIn = true;
                $this->updateLoginStatus();
                return true;
            } else {
                return false;
            }
        }catch (PDOException $exception) {
            echo json_encode(array("error" => "Error: " . $exception->getMessage()));
            return false;
        }
    }
    
    /**
     * @return bool
     */
    public function logout(): bool
    {
        $this->isLoggedIn = false;
        return true;
    }
    
    /**
     * Get user by id
     * @return boolean
     */
    public function getUserById(): bool
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }
    
    
    /**
     * @return bool
     */
    public function update(): bool
    {
        $query = "UPDATE " . $this->table_name . " SET username=:username, password=:password, email=:email WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);
        try{
            $stmt->execute();
            return true;
        }catch (PDOException $exception) {
            echo json_encode(array("error" => "Error: " . $exception->getMessage()));
            return false;
        }
    
    }
    
    /**
     * Update the login status of the user
     * @return boolean
     */
    public function updateLoginStatus(): bool
    {
        $query = "UPDATE " . $this->table_name . " SET isLoggedIn=:isLoggedIn WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->isLoggedIn = htmlspecialchars(strip_tags($this->isLoggedIn));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":isLoggedIn", $this->isLoggedIn);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * @return bool
     */
    public function delete(): bool
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * @return false|\PDOStatement
     */
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}