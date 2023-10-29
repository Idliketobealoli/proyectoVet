<?php
require_once "../common.php";

exitIfFailedSession();

$role = $_SESSION["role"];

if ($role != 0 && $_REQUEST['id'] != $_SESSION["id"]) {
    redirect('./petList.php?error=Unauthorized');
}

if (empty($_REQUEST['petId'])) { redirect('./petList.php?error=Unknown pet'); }

$pet = DAO::getPetById($_REQUEST['petId']);
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php drawHeader(); ?>
<h3 class='centered'>Pet details: <?=$pet->getName()?></h3>
<p class='centered'>Species: <?=$pet->getSpecies()?></p>
<p class='centered'>Sex: <?=$pet->isMale() ? "Male" : "Female" ?></p>
<p class='centered'>Owner: <?=$pet->getOwner()->getName()." ".$pet->getOwner()->getSurname()?></p>
<p class='centered'><?=$pet->isActive() ? "Active" : "Inactive"?></p>

<br/>
<p class='centered'><a href='../history/historyDetails.php?petId=<?=$pet->getId()?>&id=<?=$_REQUEST['id']?>'>See history.</a></p>

<?=$role == 0
    ? "<p class='centered'><a href='./petEdit.php?id=".$pet->getId()."'>Edit pet.</a></p>".
        "<p class='centered'><a href='./petDeactivate.php?id=".$pet->getId()."'>".($pet->isActive() ? 'Deactivate' : 'Activate')." pet.</a></p>".
        "<p class='centered'><a href='./petDelete.php?id=".$pet->getId()."'>Delete pet.</a></p>"
    : "" ?>

<?php drawFooter(); ?>
</body>
</html>
