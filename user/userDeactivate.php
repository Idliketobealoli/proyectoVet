<?php
require_once "../common.php";

if (empty($_REQUEST['id'])) { redirect("./userList.php?error=Unknown user"); }

if ($_SESSION['role'] == 0 || $_SESSION['id'] == $_REQUEST['id']) {
    $user = DAO::getUserById($_REQUEST['id']);
    if ($user != null) {
        DAO::switchActivityUser($user);
        redirect("./userList.php");
    }
    else redirect("./userList.php?error=Unknown user");
}
else redirect("./userList.php?error=Unauthorised");