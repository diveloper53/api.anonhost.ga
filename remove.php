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

if(!isset($_REQUEST['path'])) {
    echo json_encode(array("status" => 7, "message" => "You forgot to set a value for the [path]!"));
    exit;
}

$login = $_REQUEST['login'];
$password = $_REQUEST['password'];
$path = __DIR__ . "/../st.anonhost.ga/$login/" . str_replace("..", "", $_REQUEST['path']);

$anon_string = check_host($login, $password);
$anon = json_decode($anon_string, true);

if($anon['status'] != 0) {
    echo $anon_string;
    exit;
}

$error = is_dir($path) ? removeDirectory($path) : unlink($path);

if($error) {
    echo json_encode(array("status" => 0, "message" => "ok"));
} else {
    echo json_encode(array("status" => 8, "message" => "Failed to remove due error."));
}