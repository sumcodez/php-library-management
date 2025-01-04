<?php

// MySQL database credentials for server
const DB_SERVER = "localhost";
const DB_USERNAME = "root";
const DB_PASSWORD = "";
const DB_NAME = "library_php";


// const DB_SERVER = "localhost";
// const DB_USERNAME = "suman_dev_user";
// const DB_PASSWORD = "k&*-d^yQToKD";
// const DB_NAME = "suman_dev";



function getDatabaseConnection()
{
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Log the error
        error_log("Connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed.");
    }
}

try {
    $pdo = getDatabaseConnection();
} catch (Exception $e) {
    header("Location: ../errors/dbError.php");
    die("Error: " . $e->getMessage());
}