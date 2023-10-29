<?php
require_once "../common.php";

exitIfFailedSession();

$role = $_SESSION["role"];

if (empty($_REQUEST['id'])) { redirect('./userList.php?error=Unknown user'); }

if ($role != 0 && $_REQUEST['id'] != $_SESSION["id"]) {
    redirect('./userList.php?error=Unauthorized');
}
$user = DAO::getUserById($_REQUEST['id']);
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php drawHeader(); ?>
<h3 class='centered'>User details: <?=$user->getName()." ".$user->getSurname()?></h3>
<p class='centered'>Email: <?=$user->getEmail()?></p>
<p class='centered'>Phone: <?=$user->getPhone()?></p>
<p class='centered'>Role: <?=($user->getRole() == 0) ? "Admin" : "User"?></p>
<p class='centered'><?=$user->isActive() ? "Active" : "Inactive"?></p>

<br/>
<p class='centered'><a href='../pet/petList.php?id=<?=$user->getId()?>'>Manage pets.</a></p>

<p class='centered'><a href='./userEdit.php?id=<?=$user->getId()?>'>Edit user.</a></p>

<p class='centered'><a href='./userDeactivate.php?id=<?=$user->getId()?>'><?=$user->isActive() ? "Deactivate" : "Activate"?> user.</a></p>

<?= $role == 0 ? "<p class='centered'><a href='./userDelete.php?id=".$user->getId()."'>Delete user.</a></p>" : "" ?>

<?php drawFooter(); ?>
</body>
</html>