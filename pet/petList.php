<?php
require_once "../common.php";

exitIfFailedSession();

$role = $_SESSION["role"];

// this way only an admin can see all pets. Everyone else will just see their own pets.
if ($role == 0 && !empty($_REQUEST["name"])) { $pets = DAO::getPetsByUserNames($_REQUEST["name"]); }
else if ($role == 0) { $pets = DAO::getAllPets(null); }
else {
    !empty($_REQUEST["id"])
        ? $pets = DAO::getPetsByUser($_REQUEST["id"])
        : redirect("../user/userList.php?error=Unknown user");;
}
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php drawHeader(); ?>
<h3 class="centered">Pets</h3>

<?php if ($role == 0) {
    echo "<form action='petList.php' class='right'>" .
        "<label for='name' class='right'>Search pets by owner name: </label>" .
        "<input type='text' name='name' id='name' placeholder='Name to look out for' class='normal centered'/>" .
        "<input type='submit' value='Submit' class='centered'/><br/>" .
        "</form>";
}
?>

<table class="striped">
    <tr>
        <th>Name</th>
        <th>Species</th>
        <th>Sex</th>
        <?= $role == 0 ? "<th>Owner</th>" : "" ?>
        <?= $role == 0 ? "<th>Active</th>" : "" ?>
        <th>See history</th>
        <th></th>
    </tr>

    <?php foreach ($pets as $pet) { ?>
        <tr>
            <td><a href='petDetails.php?petId=<?=$pet->getId()?>&id=<?=$_SESSION['id']?>'><?=$pet->getName()?></a></td>
            <td><?=$pet->getSpecies()?></td>
            <td><?=$pet->isMale() ? "Male" : "Female"?></td>
            <?= $role == 0 ? "<td><a href='../user/userDetails.php?id=".$pet->getOwnerId()."'>".$pet->getOwner()->getName()." ".$pet->getOwner()->getSurname()."</a></td>" : "" ?>
            <?= $role == 0 ? "<td class='centered'>".( $pet->isActive() ? "Active" : "Inactive" )."</td>" : "" ?>
            <td class="centered"><a href='../history/historyDetails.php?petId=<?=$pet->getId()?>&id=<?=$pet->getOwnerId()?>'> 📋 </a></td>
            <td class="centered"><a href='petDeactivate.php?id=<?=$pet->getId()?>'> ✖️ </a></td>
        </tr>
    <?php } ?>
</table>

<?= $role == 0 ? "<p class='centered'><a href='petCreateForm.php'>Add pet.</a></p>" : "" ?>

<p class="right"><a href='../user/userList.php'>Go back to users.</a></p>

<?php if (!empty($_REQUEST['error'])) { echo "<br/><p class='red'>Error: ".$_REQUEST['error'].".</p>"; } ?>
<?php if (!empty($_REQUEST['message'])) { echo "<br/><p class='blue'>Message: ".$_REQUEST['message'].".</p>"; } ?>
<?php drawFooter(); ?>
</body>
</html>