<?php

// this file provides the functionality related to messages

require_once 'database.php';
require_once 'user.php';
require_once 'group.php';

// function to send a new message
// takes username of the user, groupid of the group and content of the message to be sent

function sendMessage($username, $groupid, $content) {
    // Check if user exists
    if (!userExists($username)) {
        throw new InvalidArgumentException("User '{$username}' does not exist.");
    }

    // Check if group exists
    if (!doesGroupExist($groupid)) {
        throw new InvalidArgumentException("Group '{$groupid}' does not exist.");
    }

    // Check if user is a member of the group
    if (!isUserInGroup($username, $groupid)) {
        throw new InvalidArgumentException("User '{$username}' is not a member of group '{$groupid}'.");
    }

    // Check message content
    if (empty(trim($content))) {
        throw new InvalidArgumentException("Message content cannot be empty or consist only of spaces.");
    }

    // if something is invalid, the function should raise error, otherwise proceed as belo

    // Connect to the database
    $pdo = getDatabaseConnection();

    // Preparing and executing the SQL statement
    $stmt = $pdo->prepare("INSERT INTO messages (username, groupid, content) VALUES (?, ?, ?)");
    $stmt->execute([$username, $groupid, $content]);
}

// function to list messages of a particular group
// arguments: username of the user making this request and the groupid of the group that the user wants to list the messages of

function listMessages($username, $groupid) {
    // Check if user exists
    if (!userExists($username)) {
        throw new InvalidArgumentException("User '{$username}' does not exist. <br>");
    }

    // Check if group exists
    if (!doesGroupExist($groupid)) {
        throw new InvalidArgumentException("Group '{$groupid}' does not exist. <br>");
    }

    // Check if user is part of the group
    if (!isUserInGroup($username, $groupid)) {
        throw new InvalidArgumentException("User '{$username}' is not a member of group '{$groupid}'. <br>");
    }

    // if something is invalid, the function should raise and erorr and exit, otherwise proceed as below
    // Connect to the database
    $pdo = getDatabaseConnection();

    // Preparing and executing the SQL statement
    $stmt = $pdo->prepare("SELECT 
        groups.name AS GroupName,
        users.name AS Name,
        users.username AS username,
        messages.createdat AS Timestamp,
        messages.content AS Message
    FROM 
        messages 
    JOIN 
        users ON messages.username = users.username 
    JOIN 
        groups ON messages.groupid = groups.groupid 
    WHERE 
        messages.groupid = ? 
    ORDER BY 
        messages.createdat ASC");
    $stmt->execute([$groupid]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // the below commented code was used for debugging during development
    
    // echo "<b>Messages in group '{$groupid}' listed by '{$username}':</b><br>";
    // Check if results are empty
    if (empty($results)) {
        // echo "No messages found in group '" . htmlspecialchars($groupid) . "'. <br> <br>";
        return; // Early return to prevent further execution
    }
    // echo "Messages in group '{$groupid}' listed by '{$username}':<br>";
        // foreach ($results as $message) {
        //     echo "Sent by: " . htmlspecialchars($message['UserName']) . ", ". htmlspecialchars($message['username']) . "<br>";
        //     // echo "Sender's username: " . htmlspecialchars($message['username']) . "<br>";
        //     echo "Timestamp: " . htmlspecialchars($message['Timestamp']) . "<br>";
        //     echo "Message: " . htmlspecialchars($message['Message']) . "<br><br>";
        // }
    
    return $results;
}

?>