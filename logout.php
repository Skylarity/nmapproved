<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
unset($_SESSION["user"]);

// Angular needs a status object
$reply = new stdClass();
$reply->status = 200;
$reply->message = "you are now logged out";

header("Content-type: application/json");
echo json_encode($reply);