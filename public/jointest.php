<?php

// This file was used to test the functionality of joining the group
// the test cases used below have already been tested and all of them might fail, since the groups are already
// being joined by the users, please adjust the tests below to test the functionality

require_once '../src/group.php';


function testJoinGroup($username, $groupid) {
    try {
        joinGroup($username, $groupid);
        echo "User '{$username}' joined group '{$groupid}' successfully!<br>";
    } catch (InvalidArgumentException $e) {
        echo 'Error: ' . $e->getMessage() . "<br>";
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage() . "<br>";
    }
}

// Test cases, all of these might fail since all the users have joined the groups during testing

testJoinGroup('username0', 'group0');  // should fail, group does not exist and username does not exist
testJoinGroup('username1', 'group1');  // should fail, user already exists in the group
testJoinGroup('username1', 'group7');  // should pass, new entry should be made in members
testJoinGroup('username7', 'group1');  // should pass, new entry should be made in members
testJoinGroup('username1', 'group9');   // should pass, new entry should be made in members
testJoinGroup('username1', 'group10');  // should pass, new entry should be made in members
testJoinGroup('username11', 'group1');  // should pass, new entry should be made in members
testJoinGroup('username11', '');    // should fail, group does not exist
testJoinGroup('', 'group1');    // should fail, user does not exist

// You can add more test cases as needed
?>
