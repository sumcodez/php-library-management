<?php

require_once "./config/conn.php";
require_once "./models/User.php";

class Authenticator {
    private $user;
    private $db;

    public function __construct() {
        try {
            $this->db = getDatabaseConnection();
            $this->user = new User($this->db);
        } catch (Exception $e) {
            die("Error: Unable to establish a database connection. " . $e->getMessage());
        }
    }

    // Handle Sign-up
    public function handleSignUp() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $country = $_POST['country'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['retype_password'];
            $status = 1; // Status --> active

            if ($password < 8) {
                echo "Password must have 8 characters";
                return;
            }

            if ($password !== $confirmPassword) {
                echo "Password did not matched";
                return;
            }

            $this->user->first_name = $first_name;
            $this->user->last_name = $last_name;
            $this->user->country = $country;
            $this->user->email = $email;
            $this->user->password = $password;
            $this->user->status = $status;

            if ($this->user->signUp()) {
                echo "User registered successfully.";
                header("Location: userLogin.php?success=your account is created please sign in to access the dashboard");
                exit();
            } else {
                echo "Registration failed.";
            }
        }
    }

    // Login user
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];

            if ($this->user->login()) { 
                session_start();
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_email'] = $this->user->email;

                $userDetails = $this->user->getUserById($this->user->id);
                $_SESSION['user_first_name'] = $userDetails['first_name'];
                $_SESSION['user_last_name'] = $userDetails['last_name'];
                $_SESSION['user_country'] = $userDetails['country'];
                
                header("Location: index.php?success=Logged in successfully");
                exit;
            } else {
                echo "Invalid email or password.";
            }
        }
    }

    // Logout user
    public function logout() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            $this->user->id = $_SESSION['user_id'];
            $this->user->logout();
            session_destroy();
            header("Location: userLogin.php?success=Logged out successfully");
            exit;
        }
    }

    // Handle to get all user without current user
    public function handleGetUsersWithoutCurrentUser($userId){
        return $this->user->getUsersWithoutCurrentUser($userId);
    }
}
