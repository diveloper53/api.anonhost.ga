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
$path = str_replace("..", "", $_REQUEST['path']);

$anon_string = check_host($login, $password);
$anon = json_decode($anon_string, true);

if($anon['status'] != 0) {
    echo $anon_string;
    exit;
}

$content = array_values(array_diff(scandir("../st.anonhost.ga/$login/$path"), array('.', '..')));

if($content == false)
    $content = array();

$isdir = array();
foreach($content as $element) {
    array_push($isdir, is_dir("../st.anonhost.ga/$login/$path/$element"));
}

echo json_encode(array("status" => 0, "message" => "ok", "content" => $content, "isdir" => $isdir));