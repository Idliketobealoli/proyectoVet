<?php
require_once "../common.php";

exitIfFailedSession();

$role = $_SESSION["role"];

if (empty($_REQUEST['id'])) { redirect('./petList.php?error=Unknown pet'); }

if ($role == 0) { $pet = DAO::getPetById($_REQUEST['id']); }
else redirect("./petList.php?error=Unauthorized");
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php drawHeader(); ?>
<h3  class='centered'>Rename pet: <?=$pet->getName()?></h3>

<form action='petRename.php?id=<?=$pet->getId()?>' method='post'  class='centered'>
    <label for='name'>Name</label><br>
    <input type='text' name='name' placeholder='<?=$pet->getName()?>'  class='centered'><br><br>

    <input type='submit' value='Rename'>
</form>

<?php if (!empty($_REQUEST['error'])) { echo "<br/><p class='red'>Error: ".$_REQUEST['error'].".</p>"; } ?>
<?php drawFooter(); ?>
</body>
</html>