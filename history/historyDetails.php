<?php
require_once "../common.php";

exitIfFailedSession();

$role = $_SESSION["role"];

if ($role != 0 && $_REQUEST['id'] != $_SESSION["id"]) {
    redirect('./petList.php?error=Unauthorized');
}

if (empty($_REQUEST['petId'])) { redirect('./petList.php?error=Unknown pet ID'); }

$pet = DAO::getPetById($_REQUEST['petId']);
if ($pet == null) { redirect('./petList.php?error=Unknown pet'); }

$history = DAO::getHistoryByPet($pet->getId());
if ($history == null) { redirect('./petList.php?error=History unavailable'); }
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php drawHeader(); ?>
<h3 class='centered'><?=$pet->getName()?>'s history:</h3>
<p class='centered'>Species: <?=$pet->getSpecies()?></p>
<p class='centered'>History:<br/>
    <?=$history->getHistory()?>
</p>
<p class='centered'>Observations:<br/>
    <?=$history->getObservations()?>
</p>

<br/>
<p class='centered'><a href='../pet/petDetails.php?petId=<?=$pet->getId()?>&id=<?=$_REQUEST['id']?>'>See pet details.</a></p>

<?=$role == 0
    ? "<p class='right'><a href='./historyAdd.php?petId=".$pet->getId()."'>Add more to history.</a></p>"
    : "" ?>

<?php drawFooter(); ?>
</body>
</html>
