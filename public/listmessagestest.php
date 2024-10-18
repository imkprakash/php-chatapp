<?php

// This file was used to test the functionality of listing the messages
// the test cases used below have already been tested
// please adjust the tests below to test the functionality

require_once '../src/message.php';


function testListMessages($username, $groupid) {
    try {
        // the commented code below was used for debugging purposes during development
        
        $messages = listMessages($username, $groupid);
        // if (empty($messages)){
        //     return;
        // }
        // echo "Messages in group '{$groupid}' listed by '{$username}':<br>";
        // foreach ($messages as $message) {
        //     echo "Sent by: " . htmlspecialchars($message['UserName']) . "<br>";
        //     echo "Sender's username: " . htmlspecialchars($message['username']) . "<br>";
        //     echo "Timestamp: " . htmlspecialchars($message['Timestamp']) . "<br>";
        //     echo "Message: " . htmlspecialchars($message['Message']) . "<br><br>";
        // }
    } catch (InvalidArgumentException $e) {
        echo 'Error: ' . $e->getMessage() . "<br>";
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage() . "<br>";
    }
}

// Test cases
testListMessages('username0', 'group1');  // should fail, user does not exist
testListMessages('username1', 'group0');  // should fail, user does not exist
testListMessages('username11', 'group7');  // should fail, user is not part of group
testListMessages('username1', 'group1');  // should pass
testListMessages('username1', 'group7');  // should pass
testListMessages('username1', 'group9');  // should pass
testListMessages('username1', 'group10');  // should pass
testListMessages('username7', 'group1');  // should pass
testListMessages('username7', 'group7');  // should pass
testListMessages('username7', 'group1');  // should pass
testListMessages('username11', 'group1');  // should pass
testListMessages('username11', 'group9');  // should pass
testListMessages('username11', 'group10');  // should pass
?>
