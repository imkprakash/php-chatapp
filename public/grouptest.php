<?php
// This file was used to test the functionality of the group
// the test cases used below have already been tested and all of them might fail, since the groups are already
// created, please adjust the tests below to test the functionality
require_once '../src/group.php';

function testCreateGroup($groupid, $name, $createdBy) {
    try {
        createGroup($groupid, $name, $createdBy);
        echo "Group '{$name}' created successfully!<br>";
    } catch (InvalidArgumentException $e) {
        echo 'Error: ' . $e->getMessage() . "<br>";
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage() . "<br>";
    }
}

// Test cases, all of these might fail since these are already tested during the development process

testCreateGroup('group1', 'My First Group', 'username1');  // should pass
testCreateGroup('group2!', 'Group with Special Char', 'username1');  // should fail, invalid groupid
testCreateGroup('group3', '', 'username1');  // should fail, invalid group name, empty
testCreateGroup('group4', 'Another Group', 'nonExistentUser');  // should fail, user does not exist
testCreateGroup('group5', '  Group with leading space', 'johnDoe123');  // should fail, invalid group name, leading space
testCreateGroup('group6', 'Random Group', 'username0'); // should fail, username does not exist
testCreateGroup('group7', 'This is group 7&', 'username7'); // should pass
testCreateGroup('group8 ', 'Group New', 'username7'); // should fail, space in groupid
testCreateGroup('group9', 'Group Number 9', 'username11'); // should pass
testCreateGroup('group10', 'Group !12', 'username11'); // should pass
testCreateGroup('grou11', 'Group 112', 'username11'); // should fail, groupid does not start with group

?>
