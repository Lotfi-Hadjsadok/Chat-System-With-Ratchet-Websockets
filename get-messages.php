<?php
$data = [];
require_once 'db.php';
if ($mysqli->errno) die;
$result = $mysqli->query('SELECT username,msg FROM chat');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'username' =>   $row['username'],
            'msg' => $row['msg']
        );
    }
    $mysqli->close();
    echo json_encode($data);
} else {
    $mysqli->close();
    echo json_encode(null);
}
