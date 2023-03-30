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

if(!isset($_REQUEST['count'])) {
    echo json_encode(array("status" => 8, "message" => "You forgot to set a value for the [count]!"));
    exit;
}

$login = $_REQUEST['login'];
$password = $_REQUEST['password'];
$count = $_REQUEST['count'];
$anon_string = check_host($login, $password);
$anon = json_decode($host, true);

if($anon['status'] != 0) {
    echo $anon_string;
    exit;
}

$upload = array();

// Загрузчик
for($i = 0; $i < $count; $i++) {

    $path = str_replace("..", "", $_REQUEST['path']);
    $file = __DIR__ . "/../st.anonhost.ga/$login/$path/" . basename($_FILES["file-$i"]["name"]);

    // Check file size (4096 MB = 4294967296 Bytes)
    if ($_FILES["file-$i"]["size"] > 4294967296) {
        array_push($upload, array("status" => 3, "message" => "File " . basename($_FILES["file-$i"]["name"]) ." is too large!", "id" => $i, "filename" => $_FILES["file-$i"]["name"]));
        continue;
    }

    // Check if file already exists
    if (file_exists($file)) {
        $file = __DIR__ . "/../st.anonhost.ga/$login/$path/" . basename($_FILES["file-$i"]["tmp_name"]);

        if (move_uploaded_file($_FILES["file-$i"]["tmp_name"], $file)) {
            array_push($upload, array("status" => 1, "message" => "File " . basename($_FILES["file-$i"]["name"]) ." already exists and has been renamed to " . basename($_FILES["file-$i"]["tmp_name"]) . "!", "id" => $i, "filename" => $_FILES["file-$i"]["tmp_name"]));
        } else {
            array_push($upload, array("status" => 2, "message" => "File " . basename($_FILES["file-$i"]["name"]) ." already exists and failed to upload due error.", "id" => $i, "filename" => $_FILES["file-$i"]["tmp_name"]));
        }
        continue;
    }

    if (move_uploaded_file($_FILES["file-$i"]["tmp_name"], $file)) {
        array_push($upload, array("status" => 0, "message" => "File " . basename($_FILES["file-$i"]["name"]) ." successfully uploaded!", "id" => $i, "filename" => $_FILES["file-$i"]["name"]));
    } else {
        array_push($upload, array("status" => 4, "message" => "File " . basename($_FILES["file-$i"]["name"]) ." failed to upload due error.", "id" => $i, "filename" => $_FILES["file-$i"]["name"]));
    }
}

echo json_encode(array("status" => 0, "message" => "ok", "upload" => $upload));