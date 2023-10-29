<?php
require_once "../common.php";

exitIfFailedSession();

if (empty($_REQUEST['id'])) { redirect('./userList.php?error=Unknown user'); }

if (empty($_REQUEST['password']) ||
    empty($_REQUEST['newPassword']) ||
    empty($_REQUEST['repeatPassword'])) {
    redirect('./userEdit.php?id='.$_REQUEST['id'].'&error=Incorrect fields');
}

if ($_REQUEST['newPassword'] != $_REQUEST['repeatPassword']) {
    redirect('./userEdit.php?id='.$_REQUEST['id'].'&error=New password does not match Repeat password');
}

if ($_REQUEST['password'] == $_REQUEST['newPassword']) {
    redirect('./userEdit.php?id='.$_REQUEST['id'].'&error=Previous password and new password are the same');
}

if ($_SESSION["role"] == 0 || $_SESSION['id'] == $_REQUEST['id']) {
    $user = DAO::getUserById($_REQUEST['id']);
    (DAO::changePasswordUser($user, $_REQUEST['newPassword']) != null)
        ? redirect("./userList.php?message=Updated")
        : redirect("./userList.php?error=Update failed");
}
else redirect("./userList.php?error=Unauthorised");