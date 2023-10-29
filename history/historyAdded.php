<?php
require_once "../common.php";

exitIfFailedSession();

if (empty($_REQUEST['id'])) { redirect('../pet/petList.php?error=Unknown pet'); }

if (empty($_REQUEST['history']) && empty($_REQUEST['observations'])) {
    redirect('./historyAdd.php?petId='.$_REQUEST['id'].'&error=Incorrect fields');
}

if ($_SESSION["role"] == 0) {
    $history = DAO::getHistoryByPet($_REQUEST['id']);
    if ($history) {
        if (!empty($_REQUEST['history'])) {
            $previousHistory = $history->getHistory();
            $history->setHistory($previousHistory."\n".$_REQUEST['history']);
        }
        if (!empty($_REQUEST['observations'])) {
            $previousObservations = $history->getObservations();
            $history->setObservations($previousObservations."\n".$_REQUEST['observations']);
        }

        (DAO::updateHistory($history) != null)
            ? redirect("../pet/petList.php?message=History Updated")
            : redirect("../pet/petList.php?error=History update failed");
    }
    else redirect("../pet/petList.php?error=Unknown pet");
}
else redirect("../pet/petList.php?error=Unauthorized");