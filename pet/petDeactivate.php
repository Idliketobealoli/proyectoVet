<?php
require_once "../common.php";

if (empty($_REQUEST['id'])) { redirect("./petList.php?error=Unknown pet"); }

$pet = DAO::getPetById($_REQUEST['id']);
if ($pet == null) { redirect("./petList.php?error=Unknown pet"); };

$ownerId = $pet->getOwnerId();
if ($_SESSION['role'] == 0 || $_SESSION['id'] == $ownerId) {
    DAO::switchActivityPet($pet);
    redirect("./petList.php");
}
else redirect("./petList.php?error=Unauthorised");