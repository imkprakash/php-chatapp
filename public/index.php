<?php

require '../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Nyholm\Psr7\Factory\Psr17Factory;

// Including user.php, group.php and message.php for functionality
require '../src/user.php';
require '../src/group.php';
require '../src/message.php';


$psr17Factory = new Psr17Factory();


$app = AppFactory::create($psr17Factory);

// Routes

// Home route or welcome route
$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write("Welcome to the chat application!"); // Message displayed on home page
    return $response;
});

// POST request to create a user
$app->post('/create-user', function ($request, $response, $args) {
    // Get the data from the request body
    $data = json_decode($request->getBody()->getContents(), true);

    // Extract username and name from the data
    $username = $data['username'] ?? '';
    $name = $data['name'] ?? '';

    try {
        // Calling the createUser function
        createUser($username, $name);
        $response->getBody()->write(json_encode(['message' => "User '{$username}', '{$name}' created successfully!"]));
    } catch (InvalidArgumentException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        $response = $response->withStatus(400); // Bad request from user
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
        $response = $response->withStatus(500); // Internal server error that is on db
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// POST request to create a group
$app->post('/create-group', function ($request, $response, $args) {
    // Get the data from the request body
    $data = json_decode($request->getBody()->getContents(), true);

    // Extract group details from the data
    $groupid = $data['groupid'] ?? '';      // Groupid of the new group
    $groupname = $data['groupname'] ?? '';  // Name of the new group
    $createdby = $data['username'] ?? '';   // username of the user who is creating the group

    try {
        // Calling the createGroup function
        createGroup($groupid, $groupname, $createdby);
        $response->getBody()->write(json_encode(['message' => "Group '{$groupid}' created successfully!"]));
    } catch (InvalidArgumentException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        $response = $response->withStatus(400); // Bad request from the user
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
        $response = $response->withStatus(500); // Internal server error that is on db
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// POST request to send a message
$app->post('/send-message', function ($request, $response, $args) {
    // Get the data from the request body
    $data = json_decode($request->getBody()->getContents(), true);

    // Extract message details from the data
    $username = $data['username'] ?? '';    // username of the user creating the message
    $groupid = $data['groupid'] ?? '';      // groupid of the group for which this message is intended for
    $message = $data['message'] ?? '';      // content of the message

    try {
        // Calling the sendMessage function
        sendMessage($username, $groupid, $message);
        $response->getBody()->write(json_encode(['message' => 'Message sent successfully!']));
    } catch (InvalidArgumentException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        $response = $response->withStatus(400); // Bad request from the user
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
        $response = $response->withStatus(500); // Internal server error that is on db
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// GET request to get all the messages from a group, this message will be made by a user
// This GET request can be used for refreshing the messages at regular intervals, or a trigger could be set
// such that whenever a new message arrives in a group, the user gets the new messages instantly
$app->get('/messages', function ($request, $response, $args) {
    // Get the query parameters
    $queryParams = $request->getQueryParams();
    $username = $queryParams['username'] ?? '';     // username of the usesr who is making this request
    $groupid = $queryParams['groupid'] ?? '';       // groupid of the group for which the user wants to list the message

    try {
        // Calling the listMessages function
        $messages = listMessages($username, $groupid);
        if (empty($messages)){
            $response->getBody()->write(json_encode(['message' => "No messages found in group '{$groupid}'."]));
        }
        else{
            $response->getBody()->write(json_encode($messages));    
        }
    } catch (InvalidArgumentException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        $response = $response->withStatus(400); // Bad request from user
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
        $response = $response->withStatus(500); // Internal server error that is on db
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// Run the app
$app->run();

