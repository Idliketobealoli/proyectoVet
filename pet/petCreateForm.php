<?php
require_once "../common.php";
require_once "../session/session.php";

if ($_SESSION["role"] != 0) { redirect("./petList.php?error=Unauthorized"); }

$users = DAO::getAllUsers(true);
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php if (isset($_REQUEST["error"])) { ?>
    <p class="right red">Create error.</p>
<?php } ?>

<h1 class='centered'>Register</h1>
<form action='petCreate.php' method='post' class='centered'>
    <label for='name'>Name</label><br>
    <input id="name" type='text' name='name' placeholder='Azathoth' class='centered'><br><br>

    <label for='species'>Species</label><br>
    <input id="species" type='text' name='species' placeholder='cat' class='centered'><br><br>

    <input type='checkbox' name='isMale'>Is it male?<br><br>

    <label for='owner'>Owner</label><br>
    <select id="owner" name='owner' class='centered'>
        <option value='-1' selected disabled>Owner</option>
        <?php foreach($users as $user) {
            echo "<option value='".$user->getId()."'>".$user->getName()." ".$user->getSurname()."</option>";
        }
        ?>
    </select>
    <br><br>

    <input type='submit' value='Add'>
</form>
</body>
</html>
