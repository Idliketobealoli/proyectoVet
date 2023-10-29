<?php

declare(strict_types=1);

function connectToDB(): PDO {
    $server = "localhost";
    $db = "clinica";
    $identifier = "root";
    $password = "";
    $options = [
        PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];

    try {
        $connection = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $identifier, $password, $options);
    } catch (Exception $e) {
        syso("DB Connection error: " . $e->getMessage());
        exit("DB Connection error");
    }

    return $connection;
}

function redirect(string $url) {
    header("Location: $url");
    exit;
}

function syso(string $contenido) {
    file_put_contents('php://stderr', $contenido . "\n");
}

function obtainDate(): string {
    return date("Y-m-d H:i:s");
}

function getRandomString($length) : string {
    for ($s = '', $i = 0, $z = strlen($a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')-1; $i != $length; $x = rand(0,$z), $s .= $a[$x], $i++);
    return $s;
}