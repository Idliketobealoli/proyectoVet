<?php
require_once "../common.php";

exitIfFailedSession();

if (empty($_REQUEST['id'])) { redirect('./petList.php?error=Unknown pet'); }

if (empty($_REQUEST['name'])) {
    redirect('./petEdit.php?id='.$_REQUEST['id'].'&error=Incorrect fields');
}

if ($_SESSION["role"] == 0) {
    $pet = DAO::getPetById($_REQUEST['id']);
    $pet->setName($_REQUEST["name"]);
    (DAO::updatePet($pet) != null)
        ? redirect("./petList.php?message=Updated")
        : redirect("./petList.php?error=Update failed");
}
else redirect("./petList.php?error=Unauthorized");