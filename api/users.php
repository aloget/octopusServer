<?php

require_once("../config.php");

$var = $_GET;
$token = apache_request_headers()['x-token'];

if ($var == null) {
    $userList = User::getUsersBesidesToken($token);
    echo(json_encode($userList));
} else {
    http_response_code(402);
    echo(json_encode(array('error' => "I do not understand you.",)));
}