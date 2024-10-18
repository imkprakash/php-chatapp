<?php

// this file provides the functionality related to groups

require_once 'database.php';
require_once 'user.php';

// Function to create a new group
// takes the new gorupid, name of the group and the username of the user creating the group as arguments

function createGroup($groupid, $name, $createdby) {

    // Check if groupid is empty
    if (empty(trim($groupid))) {
        throw new InvalidArgumentException("Group ID cannot be empty.");
    }


    // Check if createdby is empty
    if (empty(trim($createdby))) {
        throw new InvalidArgumentException("Created by username cannot be empty.");
    }

    // Validate groupid
    if (!validateGroupID($groupid)) {
        throw new InvalidArgumentException("Error: Invalid groupid. Must be alphanumeric and start with 'group'.");
    }

    // Validate group name
    if (!validateGroupName($name)) {
        throw new InvalidArgumentException("Error: Invalid group name. It must not be empty and should not contain leading or trailing spaces.");
    }

    // Check if the user exists in the users table
    if (!userExists($createdby)) {
        throw new InvalidArgumentException("Error: User does not exist.");
    }

    // Check if group already exists
    if (doesGroupExist($groupid)) {
        throw new InvalidArgumentException("A group with the group ID '{$groupid}' already exists.");
    }
    
    $pdo = getDatabaseConnection();

    // if something is not valid, the function should have returned without creating a new group, othwerwise
    // it will proceed as below

    // Preparing and executing the SQL statement to insert the group
    $stmt = $pdo->prepare('INSERT INTO groups (groupid, name, createdby) VALUES (?, ?, ?)');
    $stmt->execute([$groupid, $name, $createdby]);

    // Add the group creator to the members table
    if (!addGroupMember($groupid, $createdby)) {
        throw new InvalidArgumentException("Error: Failed to add the group creator to the members table.");
    }

    return "Group created successfully and group creator added to the members table.";
}

// function to join group, takes username of the user and groupid of the group
function joinGroup($username, $groupid) {
    // Check if the user exists using the users table
    if (!userExists($username)) {
        throw new InvalidArgumentException("User '{$username}' does not exist.");
    }

    // Check if the group exists using the groups table
    if (!doesGroupExist($groupid)) {
        throw new InvalidArgumentException("Group '{$groupid}' does not exist.");
    }

    // Check if the user is already a member of the group using the members table
    if (isUserInGroup($username, $groupid)) {
        throw new InvalidArgumentException("User '{$username}' is already a member of group '{$groupid}'.");
    }

    // Connecting to the database
    $pdo = getDatabaseConnection();

    // Preparing and executing the SQL statement to insert the member
    $stmt = $pdo->prepare("INSERT INTO members (groupid, username) VALUES (?, ?)");
    $stmt->execute([$groupid, $username]);
}

// Validate groupid (must start with "group" and be alphanumeric)
function validateGroupID($groupid) {
    return preg_match('/^group[a-zA-Z0-9]+$/', $groupid);
}

// Validate group name (must not be empty, no leading/trailing spaces)
function validateGroupName($name) {
    return trim($name) !== '' && $name === trim($name);
}

// Add the group creator to the members table
function addGroupMember($groupid, $username) {
    $pdo = getDatabaseConnection();

    // Prepare and execute the SQL statement to add the member
    $stmt = $pdo->prepare('INSERT INTO members (groupid, username) VALUES (?, ?)');
    return $stmt->execute([$groupid, $username]);
}

// Function to check if a group already exists
function doesGroupExist($groupid) {
    $pdo = getDatabaseConnection();

    // Preparing and executing the SQL statement
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM groups WHERE groupid = ?");
    $stmt->execute([$groupid]);
    $count = $stmt->fetchColumn();

    return $count > 0; // Returns true if group exists, false otherwise
}

// check if a user already exists in the group
function isUserInGroup($username, $groupid) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE groupid = ? AND username = ?");
    $stmt->execute([$groupid, $username]);
    return $stmt->fetchColumn() > 0;
}

?>
