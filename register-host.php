<?php

require_once 'functions.php';

const maxDays = 180;

header("Access-Control-Allow-Origin: *");

if(!isset($_REQUEST['time'])) {
    echo json_encode(array("status" => 1, "message" => "You forgot to set a value for the [time]!"));
    exit;
}

if(intval($_REQUEST['time']) <= 0) {
    echo json_encode(array("status" => 2, "message" => "[time] cannot be less then 0!"));
    exit;
}

if(intval($_REQUEST['time']) > maxDays) {
    echo json_encode(array("status" => 3, "message" => "We provide hosting up to " . maxDays . " days! Not more!"));
    exit;
}

clear(); // Очистка истёкших хостингов (анонов)

do {
    $login = str_replace(array("+", "/", "="), "", base64_encode(random_bytes(15)));
    $password = str_replace(array("+", "/", "="), "", base64_encode(random_bytes(15)));
} while(file_exists("anons/$login"));

$time = (intval($_REQUEST['time']) * 86400) + time();

mkdir("../st.anonhost.ga/$login", 0777);
file_put_contents("anons/$login", json_encode(array("login" => $login, "password" => $password, "time" => $time)));

echo json_encode(array("status" => 0, "message" => "ok", "login" => $login, "password" => $password, "time" => $time));
exit;