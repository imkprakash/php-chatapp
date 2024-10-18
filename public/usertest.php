<?php
// This file was used to test the functionality of creating a new user
// the test cases might not behave the same as new data may be added to the db
// please adjust the test cases below to test functionality

require_once '../src/user.php';

function testCreateUser($username, $name) {
    try {
        createUser($username, $name);
        echo "User '{$username}' created successfully!<br>";
    } catch (InvalidArgumentException $e) {
        echo 'Error: ' . $e->getMessage() . "<br>";
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage() . "<br>";
    }
}

// Test cases
testCreateUser('username1', 'User One');  // should pass
testCreateUser('username2', 'User  Two');  // should fail, invalid name, consecutive spaces
testCreateUser('username 3', 'User Three');  // should fail, invalid username, has space
testCreateUser('username4', '  User  ');  // should fail, leading and trailing spaces in name
testCreateUser('username5!', 'User Five');  // should fail, special char in username
testCreateUser('username6', 'User 6');  // should fail, num in name
testCreateUser('username7', 'User Seven'); // should pass
testCreateUser('username8', 'User Eight '); // should fail, trailing space in name
testCreateUser('username9', ''); // should fail, empty name
testCreateUser('', 'User Ten'); // should fail, empty username
testCreateUser('username11', 'User Eleven'); // should pass
testCreateUser(' username12', 'User Twelve'); // should fail, leading space in username

?>
