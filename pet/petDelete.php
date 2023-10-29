<?php
require_once "../common.php";

exitIfFailedSession();

if (empty($_REQUEST['id'])) { redirect("./petList.php?error=Unknown pet"); }

if ($_SESSION["role"] == 0) {
    $pet = DAO::getPetById($_REQUEST['id']);
    if ($pet != null) {
        DAO::deletePet($pet)
            ? redirect("./petList.php")
            : redirect("./petList.php?error=Cannot delete");
    }
    else redirect("./petList.php?error=Unknown pet");
}
else redirect("./petList.php?error=Unauthorized");