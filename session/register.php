<?php
require_once "../utils/utils.php";
require_once "session.php";
?>

<html>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="../stylesheet.css"/>
</head>
<body>
<?php if (isset($_REQUEST["error"])) { ?>
    <p class="right red">Register error.</p>
<?php } ?>

<?php if (isset($_REQUEST["passwordError"])) { ?>
    <p class="right blue">Passwords do not match.</p>
<?php } ?>

<h1 class='centered'>Register</h1>
<form action='checkSessionRegister.php' method='post' class='centered'>
    <label for='name'>Name</label><br>
    <input type='text' name='name' placeholder='John' class='centered'><br><br>

    <label for='surname'>Surname</label><br>
    <input type='text' name='surname' placeholder='Doe' class='centered'><br><br>

    <label for='email'>Email</label><br>
    <input type='email' name='email' placeholder='example@gmail.com' class='centered'><br><br>

    <label for='password'>Password</label><br>
    <input type='password' name='password' placeholder='50 characters max.' class='centered'><br><br>

    <label for='repeatPassword'>Repeat password</label><br>
    <input type='password' name='repeatPassword' placeholder='Type your password again.' class='centered'><br><br>

    <label for='phone'>Phone number</label><br>
    <input type='number' name='phone' placeholder='123456789' min='100000000' max='999999999' class='centered'><br><br>

    <input type='checkbox' name='rememberMe'>Remember me<br><br>

    <input type='submit' value='Register'>
</form>
<br/><p class='centered'>Already have an account? log in <a href='login.php'>here.</a></p>

</body>
</html>