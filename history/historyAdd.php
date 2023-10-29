<?php
require_once "../common.php";

exitIfFailedSession();

$role = $_SESSION["role"];

if ($role != 0) { redirect("./petList.php?error=Unauthorized"); }

if (empty($_REQUEST['petId'])) { redirect('./petList.php?error=Unknown pet'); }

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
<h3 class='centered'>Add new entries to <?=$pet->getName()?>'s history:</h3>

<form action='historyAdded.php?id=<?=$pet->getId()?>' method='post'  class='centered'>
    <label for='history'>History:</label><br>
    <input id='history' type="text" name='history' class='centered'><br><br>

    <label for='observations'>Obaservations:</label><br>
    <input id='observations' type='text' name='observations' class='centered'><br><br>

    <input type='submit' value='Add entries'>
</form>

<?php if (!empty($_REQUEST['error'])) { echo "<br/><p class='red'>Error: ".$_REQUEST['error'].".</p>"; } ?>
<?php drawFooter(); ?>
</body>
</html>
