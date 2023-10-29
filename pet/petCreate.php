<?php
require_once "../common.php";

if ($_SESSION["role"] != 0) { redirect("./petList.php?error=Unauthorized"); }

if(empty($_REQUEST["name"]) || empty($_REQUEST["species"]) ||
    empty($_REQUEST["owner"])) {
    redirect("petCreateForm.php?error");
}

$owner = DAO::getUserById($_REQUEST["owner"]);

if ($owner == null) { redirect("petCreateForm.php?error"); }

$newPet = DAO::createPet(
    $_REQUEST["name"], $_REQUEST["species"],
    isset($_REQUEST["isMale"]), true,  $_REQUEST["owner"]
);

$newPet != null
    ? redirect("petList.php")
    : redirect("petCreateForm.php?error");