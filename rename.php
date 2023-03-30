<?php

require_once 'functions.php';

header("Access-Control-Allow-Origin: *");

if(!isset($_REQUEST['login'])) {
    echo json_encode(array("status" => 1, "message" => "You forgot to set a value for the [login]!"));
    exit;
}

if(!isset($_REQUEST['password'])) {
    echo json_encode(array("status" => 2, "message" => "You forgot to set a value for the [password]!"));
    exit;
}

if(!isset($_REQUEST['from'])) {
    echo json_encode(array("status" => 7, "message" => "You forgot to set a value for the [from]!"));
    exit;
}

if(!isset($_REQUEST['to'])) {
    echo json_encode(array("status" => 8, "message" => "You forgot to set a value for the [to]!"));
    exit;
}

$login = $_REQUEST['login'];
$password = $_REQUEST['password'];
$from = __DIR__ . "/../st.anonhost.ga/$login/" . str_replace("..", "", $_REQUEST['from']);
$to = __DIR__ . "/../st.anonhost.ga/$login/" . str_replace("..", "", $_REQUEST['to']);

$anon_string = check_host($login, $password);
$anon = json_decode($anon_string, true);

if($anon['status'] != 0) {
    echo $anon_string;
    exit;
}

if(rename($from, $to)) {
    echo json_encode(array("status" => 0, "message" => "ok"));
} else {
    echo json_encode(array("status" => 9, "message" => "Failed to rename dur error."));
}