<?php

require_once './config/conn.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $first_name;
    public $last_name;
    public $country;
    public $password;
    public $email;
    public $status;
    public $last_login;
    public $last_logout;
    public $created_at;
    public $updated_at;

    // Constructor to initialize database connection
    public function __construct() {
        try {
            $this->conn = getDatabaseConnection(); // Use the provided database connection function
        } catch (Exception $e) {
            die("Error: Unable to establish a database connection. " . $e->getMessage());
        }
    }

    // Sign-up new user function [create user]
    public function signUp() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET first_name=:first_name, last_name=:last_name, country=:country, email=:email, password=:password, status=:status";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":email", $this->email);
        
        // Hash the password before storing it
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bindParam(":password", $hashedPassword);

        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

   // Login user
    public function login() {
        $query = "SELECT id, first_name, last_name, email, password, status 
                  FROM " . $this->table_name . " WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password using password_verify()
            if (password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->first_name = $row['first_name'];
                $this->last_name = $row['last_name'];
                $this->email = $row['email'];
                $this->status = $row['status'];
    
                // Update last login time
                $this->updateLastLogin();
    
                return true;
            }
        }
        return false;
    }

    // Update last login time
    private function updateLastLogin() {
        $query = "UPDATE " . $this->table_name . " SET last_login = NOW() WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
    }

    // Logout
    public function logout() {
        $query = "UPDATE " . $this->table_name . " SET last_logout = NOW() WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
    }

    // Get user by ID
    public function getUserById($userId){
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // get all user without current user
    public function getUsersWithoutCurrentUser($userId) {
        $query = "SELECT * FROM users WHERE id != :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT); 
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as an associative array
    }
}
