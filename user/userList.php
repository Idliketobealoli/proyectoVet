<?php
require_once "../common.php";

exitIfFailedSession();


$role = $_SESSION["role"];

// this way only an admin can see other users. Everyone else will just see themselves.
if ($role == 0 && !empty($_REQUEST["name"])) { $users = DAO::getUsersByName($_REQUEST["name"]); }
else if ($role == 0) { $users = DAO::getAllUsers(null); }
else {
    $user = DAO::getUserByEmail($_SESSION["email"]);
    $users = $user ? [$user] : [];
}
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php drawHeader(); ?>
<h3 class="centered">Users</h3>

<?php if ($role == 0) {
    echo "<form action='userList.php' class='right'>" .
    "<label for='name' class='right'>Search user by name: </label>" .
    "<input type='text' name='name' id='name' placeholder='Name to look out for' class='normal centered'/>" .
    "<input type='submit' value='Submit' class='centered'/><br/>" .
    "</form>";
}
?>

<table class="striped">
    <tr>
        <th>Full name</th>
        <th>Number of pets</th>
        <?= $role == 0 ? "<th>Active</th>" : "" ; ?>
        <th></th>
    </tr>

    <?php foreach ($users as $user) { ?>
        <tr>
            <td><a href='userDetails.php?id=<?=$user->getId()?>'><?=$user->getName()?> <?=$user->getSurname()?></a></td>
            <td class="centered"><?=sizeof($user->getOwnedPets())?></td>
            <?= $role == 0 ? "<td class='centered'>".( $user->isActive() ? "Active" : "Inactive" )."</td>" : "" ; ?>
            <td class="centered"><a href='userDeactivate.php?id=<?=$user->getId()?>'> ✖️ </a></td>
        </tr>
    <?php } ?>
</table>
<p class="right"><a href='../pet/petList.php<?=$role == 0 ? "" : "?id=".$users[0]->getId() ; ?>'>Manage pets.</a></p>

<?php if (!empty($_REQUEST['error'])) { echo "<br/><p class='red'>Error: ".$_REQUEST['error'].".</p>"; } ?>
<?php if (!empty($_REQUEST['message'])) { echo "<br/><p class='blue'>Message: ".$_REQUEST['message'].".</p>"; } ?>
<?php drawFooter(); ?>
</body>
</html>