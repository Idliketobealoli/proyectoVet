<?php
require_once "../common.php";

exitIfFailedSession();

$role = $_SESSION["role"];

if (empty($_REQUEST['id'])) { redirect('./userList.php?error=Unknown user'); }

if ($role == 0 || $_SESSION['id'] == $_REQUEST['id']) {
    $user = DAO::getUserById($_REQUEST['id']);
}
else redirect("./userList.php?error=Unauthorized");
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php drawHeader(); ?>
<h3  class='centered'>Edit user: <?=$user->getName()." ".$user->getSurname()?></h3>

<form action='userEdited.php?id=<?=$user->getId()?>' method='post'  class='centered'>
    <label for='name'>Name</label><br>
    <input type='text' name='name' placeholder='<?=$user->getName()?>'  class='centered'><br><br>

    <label for='surname'>Surname</label><br>
    <input type='text' name='surname' placeholder='<?=$user->getSurname()?>'  class='centered'><br><br>

    <input type='submit' value='Update'>
</form>
<br/>
<h3 class='centered'>Want to change your password?</h3>
<form action="userChangePassword.php?id=<?=$user->getId()?>" method="post" class='centered'>
    <label for='password'>Password</label><br>
    <input type='password' name='password' placeholder='50 characters max.' class='centered'><br><br>

    <label for='newPassword'>New password</label><br>
    <input type='password' name='newPassword' placeholder='50 characters max.' class='centered'><br><br>

    <label for='repeatPassword'>Repeat new password</label><br>
    <input type='password' name='repeatPassword' placeholder='Type your new password again.' class='centered'><br><br>

    <input type='submit' value='Update password'>
</form>

<?php if (!empty($_REQUEST['error'])) { echo "<br/><p class='red'>Error: ".$_REQUEST['error'].".</p>"; } ?>
<?php drawFooter(); ?>
</body>
</html>