<?php
require_once "../common.php";

exitIfFailedSession();

if (empty($_REQUEST['id'])) { redirect('./userList.php?error=Unknown user'); }

if (empty($_REQUEST['name']) ||
    empty($_REQUEST['surname'])) {
    redirect('./userEdit.php?id='.$_REQUEST['id'].'&error=Incorrect fields');
}

if ($_SESSION["role"] == 0 || $_SESSION['id'] == $_REQUEST['id']) {
    $user = DAO::getUserById($_REQUEST['id']);
    $user->setName($_REQUEST["name"]);
    $user->setSurname($_REQUEST["surname"]);
    (DAO::updateUser($user) != null)
        ? redirect("./userList.php?message=Updated")
        : redirect("./userList.php?error=Update failed");
}
else redirect("./userList.php?error=Unauthorised");