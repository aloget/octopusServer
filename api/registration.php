<?php

require_once("../config.php");

$userName = $_POST['username'];
$password = $_POST['password'];

if ($userName != null && $password != null) {
    $user = User::newUserWithClientData($userName, $password);
    if ($user == 0) {
        http_response_code(402);
        echo(json_encode(array('error' => "This username is already in use.",)));
    } else {
        $user->insert();
        echo(json_encode(array('id' => $user->id, 'username' => $user->username, 'password' => $user->password, 'token' => $user->token)));
    }
}else{
    http_response_code(402);
    echo(json_encode(array('error' => "Username or password missing.",)));
}