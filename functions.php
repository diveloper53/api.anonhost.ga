<?php

// Проверка данных на подлинность
function check_host(string $login, string $password) {

str_replace("..", "", $login);

if(!file_exists("anons/$login")) {
    return json_encode(array("status" => 3, "message" => "Anon not found."));
}

$anon = json_decode(file_get_contents("anons/$login"), true);

if(!isset($anon['login']) || !isset($anon['password']) || !isset($anon['time'])) {
    return json_encode(array("status" => 4, "message" => "The data is corrupted. Please register new hosting."));
}

if($login != $anon['login'] || $password != $anon['password']) {
    return json_encode(array("status" => 5, "message" => "Wrong login or password."));
}

if($anon['time'] - time() <= 0) {
    return json_encode(array("status" => 6, "message" => "Hosting data is out of date. Please register new hosting."));
}

return json_encode(array("status" => 0, "message" => "ok", "time" => $anon['time']));

}

// Очистка истёкших хостингов
function clear() {
    $anons = scandir("./anons/");

    foreach($anons as $file) {
        if($file != "." && $file != ".." && $file != ".htaccess") {
            $time = json_decode("./anons/" . $file, true)["time"];
            
            if($time < time()) {
                unlink("./anons/" . $file);
            }
        }
    }

    $storage = scandir("../st.anonhost.ga/");
    
    foreach($storage as $dir) {
        if($dir != "." && $dir != ".." && $dir != ".htaccess") {
            $time = json_decode("./anons/" . $dir)["time"];
            
            if($time < time()) {
                removeDirectory("../st.diveloper.ga/" . $dir);
            }
        }
    }
}

// Удаление директории вместе со всеми файлами 
function removeDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!removeDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}