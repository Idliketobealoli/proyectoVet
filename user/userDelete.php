<?php
require_once "../common.php";

exitIfFailedSession();

if (empty($_REQUEST['id'])) { redirect("./userList.php?error=Unknown user"); }

if ($_SESSION["role"] == 0) {
    $user = DAO::getUserById($_REQUEST['id']);
    if ($user != null) {
        DAO::deleteUser($user)
            ? redirect("./userList.php")
            : redirect("./userList.php?error=Cannot delete");
    }
    else redirect("./userList.php?error=Unknown user");
}
else redirect("./userList.php?error=Unauthorized");