<?php
require_once "../utils/utils.php";
require_once "session.php";

goToMainPageIfLoggedIn();

if (empty($_REQUEST["email"]) || empty($_REQUEST["password"])) {
    redirect("login.php?error");
}

$user = getUserLogin($_REQUEST["email"], $_REQUEST["password"]);

if ($user) { // Equivale a if ($user != null)
    generateRamSession($user);

    if (isset($_REQUEST["rememberMe"])) {
        generateSessionCookie();
    }

    redirect("../user/userList.php");
} else { redirect("login.php?error"); }