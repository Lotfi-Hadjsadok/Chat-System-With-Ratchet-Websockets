<?php

$response = array();
if (isset($_POST['message']) && isset($_POST['username'])) {
    $message = $_POST['message'];
    $username = $_POST['username'];
    $mysqli = new mysqli('localhost', 'root', '', 'chat');
    if ($mysqli->connect_errno) {
        die;
    }

    if (strlen($message) == 0) {
        $response['error'] = 'Empty message';
        $response['status'] = 'error';
        echo json_encode($response);
        die;
    }
    $stmt = $mysqli->prepare('INSERT INTO chat (username,msg) VALUES (?,?);');
    $stmt->bind_param('ss', $username, $message);
    $stmt->execute();
}
