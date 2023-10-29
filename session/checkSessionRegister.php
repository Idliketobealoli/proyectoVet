<?php
require_once "../common.php";

if(empty($_REQUEST["name"]) || empty($_REQUEST["surname"]) ||
    empty($_REQUEST["email"]) || empty($_REQUEST["password"]) ||
    empty($_REQUEST["repeatPassword"]) || empty($_REQUEST["phone"])) {
    redirect("register.php?error");
}

$user = DAO::getUserByEmail($_REQUEST["email"]);

if ($user) { redirect("register.php?error"); }
if ($_REQUEST["password"] != $_REQUEST["repeatPassword"]) { redirect("register.php?passwordError"); }

$newUser = DAO::createUser(
    $_REQUEST["name"], $_REQUEST["surname"],
    $_REQUEST["email"], $_REQUEST["password"],
    $_REQUEST["phone"]
);

if ($newUser) {
    $createdUser = getUserLogin($_REQUEST["email"], $_REQUEST["password"]);

    if ($createdUser) {
        generateRamSession($createdUser);

        if (isset($_REQUEST["rememberMe"])) {
            generateSessionCookie();
        }

        redirect("../user/userList.php");
    } else { redirect("register.php?error"); }
} else { redirect("register.php?error"); }