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

echo check_host($_REQUEST['login'], $_REQUEST['password']);