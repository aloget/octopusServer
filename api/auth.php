<?php

require_once("../config.php");

$userName = $_POST['username'];
$password = $_POST['password'];

if ($userName != null && $password != null) {
    $user = User::getByUserName($userName);
    if ($user == 0) {
        http_response_code(402);
        echo(json_encode(array('error' => "This \'Username\' not found.",)));
    } else {
        $md5 = md5($password);
        if ($user->password == $md5)
            echo(json_encode(array('id' => $user->id, 'username' => $user->username, 'password' => $user->password, 'token' => $user->token)));
        else {
            http_response_code(402);
            echo(json_encode(array('error' => "Password is wrong.",)));
        }
    }
} else {
    http_response_code(402);
    echo(json_encode(array('error' => "User is NULL.",)));
}