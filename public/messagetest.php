<?php

// This file was used to test the functionality of sending the messages
// the test cases below might not behave the same, since there may be new data available in the db
// please adjuts the test cases below to test the functionality

require_once '../src/message.php';

function testSendMessage($username, $groupid, $content) {
    try {
        sendMessage($username, $groupid, $content);
        echo "Message sent to group '{$groupid}' by user '{$username}' successfully!<br>";
    } catch (InvalidArgumentException $e) {
        echo 'Error: ' . $e->getMessage() . "<br>";
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage() . "<br>";
    }
}

// Test cases
testSendMessage('username0', 'group1', 'Hello, everyone!'); // should fail, user does not exist
testSendMessage('username1', 'group0', 'Hello, everyone!'); // should fail, group does not exist
testSendMessage('username7', 'group10', 'Hello, everyone!'); // should fail, user not part of group
testSendMessage('username1', 'group1', 'Hello, everyone!'); // should pass
testSendMessage('username1', 'group7', 'Hey guys!'); // should pass
testSendMessage('username1', 'group10', '   '); // should fail, message is only empty spaces
testSendMessage('username7', 'group7', '  % '); // should pass
testSendMessage('username1', 'group10', 'Hello, everyone again!'); // should pass
testSendMessage('username11', 'group10', 'Hey, how is it going?'); // should pass



?>
