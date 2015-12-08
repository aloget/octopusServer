<?php

require_once("../config.php");

$var = $_GET;

if ($var == null) {
    $userList = User::getList();
    echo(json_encode($userList));
} else {
    http_response_code(402);
    echo(json_encode(array('error' => "I do not understand you.",)));
}