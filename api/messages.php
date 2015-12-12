<?php

require_once("../config.php");

$token = apache_request_headers()['x-token'];

if ($token != null) {
    $sender_id = User::getByToken($token)->id;
    if ($_POST == null) {
        if ($_GET == null) {
            http_response_code(402);
            echo(json_encode(array('error' => "I don't understand",)));
        } else {
            $recipient_id = $_GET['recipient_id'];
            $last_message_id = $_GET['last_message_id'];

            $messages = Message::getByUsersAndMessageId($sender_id, $recipient_id, $last_message_id);
            if ($messages != false)
                echo(json_encode($messages));
            else {
                http_response_code(500);
                echo(json_encode(array('error' => "Error getting messages",)));
            }
        }
    } else {
        $recipient_id = $_POST['recipient_id'];
        $last_message_id = $_POST['last_message_id'];
        $message_text = $_POST['message_text'];

        $message = Message::withClientData($sender_id, $recipient_id, $message_text);
        $message->insert();

        $messages = Message::getByUsersAndMessageId($sender_id, $recipient_id, $last_message_id);
        if ($messages != false)
            echo(json_encode($messages));
        else {
            http_response_code(500);
            echo(json_encode(array('error' => "Error getting messages",)));
        }
    }
} else {
    http_response_code(401);
    echo(json_encode(array('error' => "Token not set.",)));
}