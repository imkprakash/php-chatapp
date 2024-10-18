<?php

// this file provides the functionality related to user creation

require_once 'database.php';

// Function to create a user
// takes username and name of the user as arguments
function createUser(string $username, string $name): void {
    
    // username empty check
    if (empty(trim($username))) {
        throw new InvalidArgumentException("Username cannot be empty.");
    }

    // name empty check
    if (empty(trim($name))) {
        throw new InvalidArgumentException("Name cannot be empty.");
    }
    
    // Validate username
    if (!isValidUsername($username)) {
        throw new InvalidArgumentException("Username must be alphanumeric and cannot contain spaces or special characters.");
    }

    // Validate name
    if (!isValidName($name)) {
        throw new InvalidArgumentException("Name must not contain leading or trailing spaces, consecutive spaces, special characters, or numeric characters.");
    }

    // Check if the user already exists
    if (userExists($username)) {
        throw new InvalidArgumentException("Username already exists.");
    }

    // if something is invalid, the function should raise an error and exit, otherwise proceed as below

    // connect to database
    $pdo = getDatabaseConnection();
    
    //Preparing and Executing the SQL statement on the users table
    $stmt = $pdo->prepare("INSERT INTO users (username, name) VALUES (?, ?)");
    $stmt->execute([$username, $name]);
}

// Function to validate the username (only alphanumeric, no spaces or special characters)
function isValidUsername(string $username): bool {
    return preg_match('/^[a-zA-Z0-9]+$/', $username);
}

// Function to validate the name (no leading/trailing spaces, no special characters, no numeric values)
function isValidName(string $name): bool {
    // Check if the name follows the rules: no numbers, special characters, leading/trailing spaces, or consecutive spaces
    return preg_match('/^(?! )[A-Za-z]+( [A-Za-z]+)*$/', $name);
}

// Function to check if a user already exists in the database users table
function userExists(string $username): bool {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();
    return $count > 0;
}

?>
