<?php
// this file provides connection to the database

function getDatabaseConnection() {
    $dbFile = __DIR__ . '/../data/chatapp.db'; // database path
    $pdo = new PDO("sqlite:$dbFile");   // initiating a php data object
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}
