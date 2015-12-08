<?php

require_once("../config.php");

$var = $_GET;
print_r($var);
//if ($var == null) {
//    $userList = Message::getList();
//    echo(json_encode($userList));
//} else {
//    http_response_code(402);
//    echo(json_encode(array('error' => "I do not understand you.",)));
//}